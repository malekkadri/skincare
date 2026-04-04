<?php

namespace App\Services\AI;

use Illuminate\Support\Arr;

class SkinAnalysisResultNormalizer
{
    private const ALLOWED_CONFIDENCE = ['low', 'medium', 'high', 'uncertain'];

    private const ALLOWED_SEVERITY = ['low', 'medium', 'high', 'uncertain'];

    private const ALLOWED_SKIN_TYPE = ['dry', 'oily', 'combination', 'normal', 'sensitive', 'uncertain'];

    private const ALLOWED_LEVEL = ['low', 'medium', 'high', 'uncertain'];

    public function normalize(array $result, array $serviceCatalog): array
    {
        $allowedById = collect($serviceCatalog)->keyBy('id');
        $allowedBySlug = collect($serviceCatalog)->keyBy('slug');

        $recommended = collect($result['recommended_services'] ?? [])
            ->map(function ($item) use ($allowedById, $allowedBySlug): ?array {
                if (! is_array($item)) {
                    return null;
                }

                $serviceId = (int) ($item['service_id'] ?? 0);
                $slug = trim((string) ($item['slug'] ?? ''));
                $matched = $allowedById->get($serviceId) ?? ($slug !== '' ? $allowedBySlug->get($slug) : null);

                if (! $matched) {
                    return null;
                }

                return [
                    'service_id' => (int) $matched['id'],
                    'slug' => (string) $matched['slug'],
                    'score' => $this->floatBetween($item['score'] ?? 0, 0, 1),
                    'priority' => max(1, min(25, (int) ($item['priority'] ?? 999))),
                    'reason' => $this->cleanText($item['reason'] ?? '', 300),
                    'cautions' => collect($item['cautions'] ?? [])->map(fn ($value) => $this->cleanText($value, 180))->filter()->take(8)->values()->all(),
                ];
            })
            ->filter()
            ->sortBy('priority')
            ->values();

        $visibleConcerns = collect($result['visible_concerns'] ?? [])->map(function ($item): ?array {
            if (! is_array($item)) {
                return null;
            }

            $confidenceLabel = strtolower(trim((string) ($item['confidence_label'] ?? '')));

            return [
                'key' => $this->normalizeConcernKey($item['key'] ?? 'uncertain'),
                'confidence' => $this->floatBetween($item['confidence'] ?? 0, 0, 1),
                'confidence_label' => in_array($confidenceLabel, self::ALLOWED_CONFIDENCE, true) ? $confidenceLabel : 'uncertain',
                'severity' => $this->enumOrDefault($item['severity'] ?? null, self::ALLOWED_SEVERITY, 'uncertain'),
                'reason' => $this->cleanText($item['reason'] ?? '', 220),
            ];
        })->filter()->take(12)->values()->all();

        $notRecommendedIds = collect($result['not_recommended_service_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter(fn (int $id) => $allowedById->has($id))
            ->values()
            ->all();

        $imageIssues = collect(Arr::get($result, 'image_quality.issues', []))
            ->map(fn ($value) => $this->cleanText($value, 120))
            ->filter()
            ->take(8)
            ->values()
            ->all();

        $cautionFlags = collect($result['caution_flags'] ?? [])
            ->map(fn ($value) => $this->cleanText($value, 150))
            ->filter()
            ->take(12)
            ->values()
            ->all();

        $confidence = $recommended->avg('score');
        $hasHighRiskConcern = collect($visibleConcerns)->contains(fn (array $concern) => $concern['severity'] === 'high' && $concern['confidence'] >= 0.5);
        $imageSufficient = (bool) Arr::get($result, 'image_quality.is_sufficient', false);
        $needsHumanReview = (bool) ($result['needs_human_review'] ?? false)
            || ! $imageSufficient
            || $recommended->isEmpty()
            || $hasHighRiskConcern
            || $confidence === null
            || $confidence < 0.45;
        $referToDerm = (bool) ($result['refer_to_dermatologist'] ?? false) || $hasHighRiskConcern;

        $userSummary = $this->safeSummary($result['user_facing_summary'] ?? '', 900, false);
        $adminSummary = $this->safeSummary($result['admin_summary'] ?? '', 1200, true);

        return [
            'analysis_version' => $this->cleanText($result['analysis_version'] ?? '1.0', 20) ?: '1.0',
            'image_quality' => [
                'is_sufficient' => $imageSufficient,
                'issues' => $imageIssues,
            ],
            'skin_profile' => [
                'skin_type_guess' => $this->enumOrDefault(Arr::get($result, 'skin_profile.skin_type_guess'), self::ALLOWED_SKIN_TYPE, 'uncertain'),
                'sensitivity_level' => $this->enumOrDefault(Arr::get($result, 'skin_profile.sensitivity_level'), self::ALLOWED_LEVEL, 'uncertain'),
                'hydration_level' => $this->enumOrDefault(Arr::get($result, 'skin_profile.hydration_level'), self::ALLOWED_LEVEL, 'uncertain'),
            ],
            'visible_concerns' => $visibleConcerns,
            'recommended_services' => $recommended->all(),
            'not_recommended_service_ids' => $notRecommendedIds,
            'caution_flags' => $cautionFlags,
            'needs_human_review' => $needsHumanReview,
            'refer_to_dermatologist' => $referToDerm,
            'user_facing_summary' => $userSummary,
            'admin_summary' => $adminSummary,
            'confidence_score' => $confidence !== null ? round((float) $confidence, 3) : null,
        ];
    }

    private function enumOrDefault(mixed $value, array $allowed, string $default): string
    {
        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, $allowed, true) ? $normalized : $default;
    }

    private function normalizeConcernKey(mixed $value): string
    {
        $key = strtolower(trim((string) $value));
        $key = preg_replace('/[^a-z0-9_\-]+/', '_', $key) ?: 'uncertain';

        return mb_substr($key, 0, 60);
    }

    private function cleanText(mixed $value, int $maxLength): string
    {
        if (! is_scalar($value)) {
            return '';
        }

        $text = trim(preg_replace('/\s+/', ' ', (string) $value) ?? '');

        return mb_substr($text, 0, $maxLength);
    }

    private function floatBetween(mixed $value, float $min, float $max): float
    {
        $float = is_numeric($value) ? (float) $value : $min;

        return max($min, min($max, $float));
    }

    private function safeSummary(mixed $summary, int $maxLength, bool $allowOperational): string
    {
        $text = $this->cleanText($summary, $maxLength);
        if ($text === '') {
            return '';
        }

        $blockedTerms = ['diagnosis', 'diagnose', 'disease', 'medical certainty', 'guaranteed cure'];
        foreach ($blockedTerms as $term) {
            if (str_contains(strtolower($text), $term)) {
                return $allowOperational
                    ? 'AI output required moderation due to safety wording. Human review is required.'
                    : 'Thanks for sharing your photos. A specialist should review this before recommendations are finalized.';
            }
        }

        return $text;
    }
}
