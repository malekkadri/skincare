<?php

namespace Tests\Unit;

use App\Services\AI\SkinAnalysisResultNormalizer;
use Tests\TestCase;

class SkinAnalysisResultNormalizerTest extends TestCase
{
    public function test_it_filters_hallucinated_services_and_enforces_safe_defaults(): void
    {
        $normalizer = new SkinAnalysisResultNormalizer;

        $catalog = [
            ['id' => 10, 'slug' => 'hydrating-facial'],
            ['id' => 11, 'slug' => 'deep-cleanse'],
        ];

        $result = $normalizer->normalize([
            'image_quality' => ['is_sufficient' => false, 'issues' => ['low_light']],
            'visible_concerns' => [
                ['key' => 'Texture', 'confidence' => 0.8, 'severity' => 'critical', 'reason' => 'very visible'],
            ],
            'recommended_services' => [
                ['service_id' => 10, 'slug' => 'hydrating-facial', 'score' => 0.9, 'priority' => 2],
                ['service_id' => 999, 'slug' => 'imaginary', 'score' => 1.2, 'priority' => 1],
            ],
            'not_recommended_service_ids' => [11, 998],
            'user_facing_summary' => 'Possible diagnosis from image.',
            'admin_summary' => 'Possible diagnosis from image.',
        ], $catalog);

        $this->assertCount(1, $result['recommended_services']);
        $this->assertSame(10, $result['recommended_services'][0]['service_id']);
        $this->assertSame([11], $result['not_recommended_service_ids']);
        $this->assertSame('uncertain', $result['visible_concerns'][0]['severity']);
        $this->assertTrue($result['needs_human_review']);
        $this->assertStringContainsString('specialist should review', strtolower($result['user_facing_summary']));
    }
}
