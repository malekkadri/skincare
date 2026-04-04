<?php

namespace App\Services\AI;

use App\Models\ConsultationImage;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;
use Throwable;

class GrokSkinAnalysisService
{
    public function __construct(protected SkinAnalysisResultNormalizer $normalizer)
    {
    }

    public function analyze(array $context, array $serviceCatalog, array $images): array
    {
        if (! config('services.xai.enabled', true)) {
            throw new RuntimeException('Face analysis provider is disabled by configuration.');
        }

        if (! config('services.xai.api_key')) {
            throw new RuntimeException('Face analysis provider key is not configured.');
        }

        $response = $this->client()->post(rtrim((string) config('services.xai.base_url'), '/').'/responses', [
            'model' => config('services.xai.vision_model'),
            'store' => (bool) config('services.xai.store', false),
            'input' => [
                [
                    'role' => 'developer',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => $this->developerInstruction(),
                    ]],
                ],
                [
                    'role' => 'user',
                    'content' => array_merge([
                        [
                            'type' => 'input_text',
                            'text' => $this->buildUserPayload($context, $serviceCatalog),
                        ],
                    ], $this->imagePayloads($images)),
                ],
            ],
            'text' => [
                'format' => [
                    'type' => 'json_object',
                ],
            ],
        ]);

        $response->throw();

        $payload = $response->json();
        $outputText = Arr::get($payload, 'output.0.content.0.text')
            ?? Arr::get($payload, 'output_text');

        if (! is_string($outputText) || trim($outputText) === '') {
            throw new RuntimeException('xAI response did not contain a JSON text payload.');
        }

        $decoded = json_decode($outputText, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('xAI returned invalid JSON for skin analysis.');
        }

        return [
            'provider' => 'xai',
            'model' => (string) (Arr::get($payload, 'model') ?: config('services.xai.vision_model')),
            'raw_response' => $this->safeRawResponse($payload, $outputText),
            'normalized' => $this->normalizer->normalize($decoded, $serviceCatalog),
        ];
    }

    public function normalizeResult(array $result, array $serviceCatalog): array
    {
        return $this->normalizer->normalize($result, $serviceCatalog);
    }

    public function classifyFailure(Throwable $exception): string
    {
        if ($exception instanceof ConnectionException) {
            return 'network';
        }

        $message = strtolower($exception->getMessage());

        return match (true) {
            str_contains($message, 'timed out') => 'timeout',
            str_contains($message, 'invalid json') => 'malformed_response',
            str_contains($message, 'did not contain a json text payload') => 'partial_response',
            str_contains($message, 'not configured') || str_contains($message, 'disabled by configuration') => 'provider_unavailable',
            default => 'provider_error',
        };
    }

    protected function imagePayloads(array $images): array
    {
        return collect($images)->map(function (ConsultationImage $image): array {
            $binary = \Storage::disk($image->disk)->get($image->path);
            $mime = $image->mime_type ?: 'image/jpeg';

            return [
                'type' => 'input_image',
                'image_url' => 'data:'.$mime.';base64,'.base64_encode($binary),
            ];
        })->values()->all();
    }

    protected function developerInstruction(): string
    {
        return 'You are a skincare recommendation assistant for a premium skincare studio. Analyze only visible non-medical skin presentation from provided face images and form inputs. Never diagnose diseases or medical conditions. If severe, uncertain, risky, or image quality is poor, set needs_human_review=true and optionally refer_to_dermatologist=true. Recommend services only from supplied service catalog and never invent names, prices, or durations. Return strict JSON only, no markdown, no prose outside JSON.';
    }

    protected function buildUserPayload(array $context, array $serviceCatalog): string
    {
        return json_encode([
            'task' => 'Analyze face images and suggest skincare services safely.',
            'context' => $context,
            'service_catalog' => $serviceCatalog,
            'rules' => [
                'recommend only from service_catalog',
                'recommended_services must include service_id and slug from catalog',
                'if uncertain return empty recommendations and caution flags',
                'no diagnosis and no medical claims',
                'sort recommendations by ascending priority',
            ],
            'required_json_shape' => [
                'analysis_version' => '1.0',
                'image_quality' => ['is_sufficient' => true, 'issues' => ['low_light']],
                'skin_profile' => ['skin_type_guess' => 'uncertain', 'sensitivity_level' => 'uncertain', 'hydration_level' => 'uncertain'],
                'visible_concerns' => [['key' => 'texture', 'confidence' => 0.0, 'severity' => 'uncertain', 'reason' => 'short explanation']],
                'recommended_services' => [['service_id' => 1, 'slug' => 'example', 'score' => 0.0, 'priority' => 1, 'reason' => 'short reason', 'cautions' => ['short note']]],
                'not_recommended_service_ids' => [1],
                'caution_flags' => ['image quality limited'],
                'needs_human_review' => true,
                'refer_to_dermatologist' => false,
                'user_facing_summary' => 'short calm premium explanation for the client',
                'admin_summary' => 'slightly more detailed operational summary for staff',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function client(): PendingRequest
    {
        return Http::asJson()
            ->acceptJson()
            ->withToken((string) config('services.xai.api_key'))
            ->timeout((int) config('services.xai.timeout', 30))
            ->connectTimeout((int) config('services.xai.connect_timeout', 10))
            ->retry(2, 300, throw: false);
    }

    private function safeRawResponse(array $payload, string $outputText): array
    {
        return [
            'id' => Arr::get($payload, 'id'),
            'model' => Arr::get($payload, 'model'),
            'status' => Arr::get($payload, 'status'),
            'created_at' => Arr::get($payload, 'created_at'),
            'usage' => Arr::get($payload, 'usage', []),
            'output_text_excerpt' => mb_substr($outputText, 0, (int) config('services.xai.max_output_chars', 4000)),
        ];
    }
}
