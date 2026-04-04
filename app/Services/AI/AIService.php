<?php

namespace App\Services\AI;

use App\Models\AiUsageLog;
use App\Models\Consultation;
use App\Models\Service;
use App\Models\Setting;
use App\Support\AI\PromptBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Throwable;

class AIService
{
    public function __construct(
        protected PromptBuilder $promptBuilder,
    ) {
    }

    public function summarizeConsultation(Consultation $consultation): array
    {
        return $this->runFeature('consultation_summary', function () use ($consultation): array {
            $services = $this->activeServicesForPrompt($consultation->preferred_language);
            $prompt = $this->promptBuilder->consultationSummaryPrompt($consultation, $services);
            $result = $this->provider()->generateJson($prompt, 'You are a safe skincare assistant for internal admin summarization.');

            return [
                'status' => 'success',
                'provider' => $result['provider'],
                'model' => $result['model'],
                'summary_text' => Arr::get($result, 'data.summary_text'),
                'recommended_services' => Arr::get($result, 'data.recommended_services', []),
                'risk_flags' => Arr::get($result, 'data.risk_flags', []),
                'suggested_next_step' => Arr::get($result, 'data.suggested_next_step'),
                'caution_notes' => Arr::get($result, 'data.caution_notes'),
                'raw_response' => $result['raw_response'],
            ];
        }, $consultation);
    }

    public function recommendServices(array $consultationData): array
    {
        return $this->runFeature('service_recommendation', function () use ($consultationData): array {
            $language = Arr::get($consultationData, 'preferred_language', app()->getLocale());
            $services = $this->activeServicesForPrompt($language);
            $prompt = $this->promptBuilder->serviceRecommendationPrompt($consultationData, $services);
            $result = $this->provider()->generateJson($prompt, 'You are a skincare service recommendation assistant.');

            return [
                'status' => 'success',
                'provider' => $result['provider'],
                'model' => $result['model'],
                'recommended_services' => Arr::get($result, 'data.recommended_services', []),
                'explanation_summary' => Arr::get($result, 'data.explanation_summary'),
                'caution_notes' => Arr::get($result, 'data.caution_notes'),
                'suggested_next_step' => Arr::get($result, 'data.suggested_next_step'),
                'raw_response' => $result['raw_response'],
            ];
        });
    }

    public function generateContent(array $input, string $type, ?int $adminUserId = null): array
    {
        return $this->runFeature('content_helper', function () use ($input, $type): array {
            $prompt = $this->promptBuilder->contentPrompt($input, $type);
            $result = $this->provider()->generateJson($prompt, 'You are a bilingual skincare content helper for admin draft writing.');

            return [
                'status' => 'success',
                'provider' => $result['provider'],
                'model' => $result['model'],
                'title' => Arr::get($result, 'data.title'),
                'body' => Arr::get($result, 'data.body'),
                'cta' => Arr::get($result, 'data.cta'),
                'raw_response' => $result['raw_response'],
            ];
        }, null, $adminUserId, Arr::get($input, 'context'));
    }

    protected function runFeature(string $featureKey, callable $callback, ?Consultation $consultation = null, ?int $adminUserId = null, ?string $contextSummary = null): array
    {
        $settings = Setting::current();

        if (! $this->featureEnabled($featureKey, $settings)) {
            return $this->skip($featureKey, 'AI feature disabled in settings.', $consultation?->id, $adminUserId, $contextSummary);
        }

        if (! $settings->ai_enabled || ! $settings->ai_api_key) {
            return $this->skip($featureKey, 'AI unavailable (disabled or missing API key).', $consultation?->id, $adminUserId, $contextSummary);
        }

        try {
            $result = $callback();

            $this->logUsage($featureKey, $result['provider'] ?? $settings->ai_provider, $result['model'] ?? $settings->ai_model, 'success', null, $consultation?->id, $adminUserId, $contextSummary, $result['summary_text'] ?? $result['explanation_summary'] ?? ($result['body'] ?? null));

            return $result;
        } catch (Throwable $exception) {
            Log::warning('AI feature failed', ['feature' => $featureKey, 'error' => $exception->getMessage()]);
            $this->logUsage($featureKey, $settings->ai_provider, $settings->ai_model, 'failed', $exception->getMessage(), $consultation?->id, $adminUserId, $contextSummary);

            return [
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'provider' => $settings->ai_provider,
                'model' => $settings->ai_model,
            ];
        }
    }

    protected function skip(string $featureKey, string $message, ?int $consultationId = null, ?int $adminUserId = null, ?string $contextSummary = null): array
    {
        $settings = Setting::current();
        $this->logUsage($featureKey, $settings->ai_provider, $settings->ai_model, 'skipped', $message, $consultationId, $adminUserId, $contextSummary);

        return [
            'status' => 'skipped',
            'error_message' => $message,
            'provider' => $settings->ai_provider,
            'model' => $settings->ai_model,
        ];
    }

    protected function featureEnabled(string $featureKey, Setting $settings): bool
    {
        return match ($featureKey) {
            'consultation_summary' => (bool) $settings->ai_enable_consultation_summary,
            'service_recommendation' => (bool) $settings->ai_enable_service_recommendation,
            'content_helper' => (bool) $settings->ai_enable_admin_content_helper,
            default => false,
        };
    }

    protected function provider(): GrokService
    {
        return new GrokService(Setting::current());
    }

    protected function activeServicesForPrompt(string $language = 'fr'): array
    {
        return Service::query()->active()->ordered()->get()->map(function (Service $service) use ($language): array {
            $name = $language === 'fr' ? $service->name_fr : $service->name_en;
            $description = $language === 'fr' ? $service->short_description_fr : $service->short_description_en;

            return [
                'id' => $service->id,
                'slug' => $service->slug,
                'name' => $name,
                'description' => $description,
                'duration_minutes' => $service->duration_minutes,
            ];
        })->values()->all();
    }

    protected function logUsage(string $featureKey, ?string $provider, ?string $model, string $status, ?string $errorMessage = null, ?int $consultationId = null, ?int $adminUserId = null, ?string $inputSummary = null, ?string $outputSummary = null): void
    {
        AiUsageLog::query()->create([
            'feature_key' => $featureKey,
            'provider' => $provider,
            'model' => $model,
            'status' => $status,
            'error_message' => $errorMessage,
            'related_consultation_id' => $consultationId,
            'admin_user_id' => $adminUserId,
            'input_context_summary' => $inputSummary,
            'output_summary' => $outputSummary ? mb_substr($outputSummary, 0, 500) : null,
        ]);
    }
}
