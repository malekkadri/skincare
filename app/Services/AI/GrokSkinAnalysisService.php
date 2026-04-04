<?php

namespace App\Services\AI;

use App\Models\ConsultationImage;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class GrokSkinAnalysisService
{
    public function analyze(array $context, array $serviceCatalog, array $images): array
    {
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
            'model' => (string) config('services.xai.vision_model'),
            'raw_response' => $payload,
            'normalized' => $this->normalizeResult($decoded, $serviceCatalog),
        ];
    }

    public function normalizeResult(array $result, array $serviceCatalog): array
    {
        $allowedById = collect($serviceCatalog)->keyBy('id');
        $allowedBySlug = collect($serviceCatalog)->keyBy('slug');

        $recommended = collect($result['recommended_services'] ?? [])
            ->map(function ($item) use ($allowedById, $allowedBySlug): ?array {
                if (! is_array($item)) {
                    return null;
                }

                $serviceId = (int) ($item['service_id'] ?? 0);
                $slug = (string) ($item['slug'] ?? '');
                $matched = $allowedById->get($serviceId) ?? ($slug !== '' ? $allowedBySlug->get($slug) : null);

                if (! $matched) {
                    return null;
                }

                return [
                    'service_id' => (int) $matched['id'],
                    'slug' => (string) $matched['slug'],
                    'score' => max(0.0, min(1.0, (float) ($item['score'] ?? 0))),
                    'priority' => max(1, (int) ($item['priority'] ?? 999)),
                    'reason' => (string) ($item['reason'] ?? ''),
                    'cautions' => collect($item['cautions'] ?? [])->filter(fn ($value) => is_string($value) && $value !== '')->values()->all(),
                ];
            })
            ->filter()
            ->sortBy('priority')
            ->values();

        $visibleConcerns = collect($result['visible_concerns'] ?? [])->map(function ($item): ?array {
            if (! is_array($item)) {
                return null;
            }

            return [
                'key' => (string) ($item['key'] ?? 'uncertain'),
                'confidence' => max(0.0, min(1.0, (float) ($item['confidence'] ?? 0))),
                'severity' => (string) ($item['severity'] ?? 'uncertain'),
                'reason' => (string) ($item['reason'] ?? ''),
            ];
        })->filter()->values()->all();

        $confidence = $recommended->avg('score');

        return [
            'analysis_version' => (string) ($result['analysis_version'] ?? '1.0'),
            'image_quality' => [
                'is_sufficient' => (bool) Arr::get($result, 'image_quality.is_sufficient', false),
                'issues' => collect(Arr::get($result, 'image_quality.issues', []))->filter(fn ($v) => is_string($v))->values()->all(),
            ],
            'skin_profile' => [
                'skin_type_guess' => (string) Arr::get($result, 'skin_profile.skin_type_guess', 'uncertain'),
                'sensitivity_level' => (string) Arr::get($result, 'skin_profile.sensitivity_level', 'uncertain'),
                'hydration_level' => (string) Arr::get($result, 'skin_profile.hydration_level', 'uncertain'),
            ],
            'visible_concerns' => $visibleConcerns,
            'recommended_services' => $recommended->all(),
            'not_recommended_service_ids' => collect($result['not_recommended_service_ids'] ?? [])->map(fn ($id) => (int) $id)->filter()->values()->all(),
            'caution_flags' => collect($result['caution_flags'] ?? [])->filter(fn ($value) => is_string($value) && $value !== '')->values()->all(),
            'needs_human_review' => (bool) ($result['needs_human_review'] ?? false),
            'refer_to_dermatologist' => (bool) ($result['refer_to_dermatologist'] ?? false),
            'user_facing_summary' => (string) ($result['user_facing_summary'] ?? ''),
            'admin_summary' => (string) ($result['admin_summary'] ?? ''),
            'confidence_score' => $confidence !== null ? round((float) $confidence, 3) : null,
        ];
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
            ->timeout((int) config('services.xai.timeout', 30));
    }
}
