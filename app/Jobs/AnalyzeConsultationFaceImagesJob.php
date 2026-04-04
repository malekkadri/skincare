<?php

namespace App\Jobs;

use App\Models\AiUsageLog;
use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\Service;
use App\Services\AI\GrokSkinAnalysisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class AnalyzeConsultationFaceImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(public int $consultationId)
    {
    }

    public function middleware(): array
    {
        return [
            (new WithoutOverlapping('consultation-face-analysis-'.$this->consultationId))
                ->releaseAfter(30)
                ->expireAfter(180),
        ];
    }

    public function backoff(): array
    {
        return [60, 300, 900];
    }

    public function handle(GrokSkinAnalysisService $analysisService): void
    {
        $startedAt = microtime(true);

        $consultation = Consultation::query()
            ->with(['images', 'latestAiResult'])
            ->findOrFail($this->consultationId);

        $lock = Cache::lock('consultation-face-analysis-run-'.$consultation->id, 180);

        try {
            $lock->block(3);
        } catch (LockTimeoutException) {
            $this->release(30);

            return;
        }

        try {
            if ($consultation->images->isEmpty()) {
                $this->markAsSkipped($consultation->id, 'No face images available for analysis.');

                return;
            }

            $aiResult = DB::transaction(function () use ($consultation): ?ConsultationAiResult {
                $record = ConsultationAiResult::query()->lockForUpdate()->firstOrCreate(
                    ['consultation_id' => $consultation->id],
                    ['status' => 'pending', 'generated_at' => now()]
                );

                if ($record->status === 'processing') {
                    return null;
                }

                $record->update([
                    'status' => 'processing',
                    'error_message' => null,
                ]);

                return $record;
            });

            if (! $aiResult) {
                return;
            }

            $services = Service::query()
                ->active()
                ->with('category:id,name_fr,name_en')
                ->ordered()
                ->get()
                ->map(function (Service $service) use ($consultation): array {
                    $isFr = $consultation->preferred_language === 'fr';

                    return [
                        'id' => $service->id,
                        'slug' => $service->slug,
                        'name' => $isFr ? $service->name_fr : $service->name_en,
                        'short_description' => $isFr ? $service->short_description_fr : $service->short_description_en,
                        'category_name' => $isFr ? ($service->category?->name_fr ?? '') : ($service->category?->name_en ?? ''),
                        'duration_minutes' => $service->duration_minutes,
                        'price' => $isFr ? $service->price_tnd : $service->price_eur,
                        'featured' => (bool) $service->is_featured,
                    ];
                })->values()->all();

            $analysis = $analysisService->analyze([
                'flow' => 'consultation',
                'preferred_language' => $consultation->preferred_language,
                'skin_type' => $consultation->skin_type,
                'skin_sensitivity_level' => $consultation->skin_sensitivity_level,
                'main_concerns' => $consultation->main_concerns,
                'preferred_goals' => $consultation->preferred_goals,
                'additional_notes' => $consultation->additional_notes,
            ], $services, $consultation->images->all());

            DB::transaction(function () use ($analysis, $aiResult, $consultation, $startedAt): void {
                $normalized = $analysis['normalized'];
                $completedAt = now();

                $aiResult->refresh();
                $aiResult->update([
                    'provider' => $analysis['provider'],
                    'model' => $analysis['model'],
                    'summary_text' => $normalized['user_facing_summary'] ?: $normalized['admin_summary'],
                    'user_summary' => $normalized['user_facing_summary'],
                    'admin_summary' => $normalized['admin_summary'],
                    'recommended_services_json' => $normalized['recommended_services'],
                    'risk_flags_json' => $normalized['caution_flags'],
                    'raw_response_json' => $analysis['raw_response'],
                    'normalized_result_json' => $normalized,
                    'status' => 'completed',
                    'confidence_score' => $normalized['confidence_score'],
                    'needs_human_review' => $normalized['needs_human_review'],
                    'refer_to_dermatologist' => $normalized['refer_to_dermatologist'],
                    'error_message' => null,
                    'generated_at' => $completedAt,
                    'processed_at' => $completedAt,
                ]);

                AiUsageLog::query()->create([
                    'feature_key' => 'consultation_face_analysis',
                    'provider' => $analysis['provider'],
                    'model' => $analysis['model'],
                    'status' => 'success',
                    'related_consultation_id' => $consultation->id,
                    'input_context_summary' => 'Consultation face image analysis; images='.$consultation->images->count().'; duration_ms='.(int) ((microtime(true) - $startedAt) * 1000),
                    'output_summary' => mb_substr((string) ($normalized['admin_summary'] ?: $normalized['user_facing_summary']), 0, 500),
                ]);
            });
        } catch (Throwable $exception) {
            $category = $analysisService->classifyFailure($exception);
            $this->markAsFailed($consultation->id, '['.$category.'] '.$exception->getMessage(), $category, $startedAt);

            throw $exception;
        } finally {
            optional($lock)->release();
        }
    }

    private function markAsSkipped(int $consultationId, string $message): void
    {
        DB::transaction(function () use ($consultationId, $message): void {
            $aiResult = ConsultationAiResult::query()->lockForUpdate()->firstOrCreate(
                ['consultation_id' => $consultationId],
                ['status' => 'pending', 'generated_at' => now()]
            );

            $aiResult->update([
                'status' => 'skipped',
                'error_message' => $message,
                'processed_at' => now(),
            ]);
        });
    }

    private function markAsFailed(int $consultationId, string $errorMessage, string $category, float $startedAt): void
    {
        DB::transaction(function () use ($consultationId, $errorMessage, $category, $startedAt): void {
            $aiResult = ConsultationAiResult::query()->lockForUpdate()->firstOrCreate(
                ['consultation_id' => $consultationId],
                ['status' => 'pending', 'generated_at' => now()]
            );

            $aiResult->update([
                'status' => 'failed',
                'error_message' => mb_substr($errorMessage, 0, 1500),
                'processed_at' => now(),
            ]);

            AiUsageLog::query()->create([
                'feature_key' => 'consultation_face_analysis',
                'provider' => 'xai',
                'model' => config('services.xai.vision_model'),
                'status' => 'failed',
                'error_message' => '['.$category.'] '.mb_substr($errorMessage, 0, 1000),
                'related_consultation_id' => $consultationId,
                'input_context_summary' => 'Consultation face image analysis; duration_ms='.(int) ((microtime(true) - $startedAt) * 1000),
            ]);
        });
    }
}
