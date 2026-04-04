<?php

namespace App\Support\AI;

use App\Models\Consultation;

class PromptBuilder
{
    public function consultationSummaryPrompt(Consultation $consultation, array $services): string
    {
        return json_encode([
            'brand' => 'Skin by Noor',
            'market' => 'Tunisia',
            'constraints' => [
                'No medical diagnosis',
                'No guaranteed results',
                'Use cautious language: may be suitable, manual review recommended',
                'Recommendations must be selected from allowed services list only',
            ],
            'task' => 'Summarize a skincare consultation for admin review in concise premium language.',
            'consultation' => [
                'preferred_language' => $consultation->preferred_language,
                'name' => $consultation->full_name,
                'skin_type' => $consultation->skin_type,
                'skin_sensitivity_level' => $consultation->skin_sensitivity_level,
                'main_concerns' => $consultation->main_concerns,
                'allergies' => $consultation->allergies,
                'current_products' => $consultation->current_products,
                'current_treatments_or_medications' => $consultation->current_treatments_or_medications,
                'pregnancy_or_breastfeeding_status' => $consultation->pregnancy_or_breastfeeding_status,
                'preferred_goals' => $consultation->preferred_goals,
                'additional_notes' => $consultation->additional_notes,
            ],
            'allowed_services' => $services,
            'required_json_shape' => [
                'summary_text' => 'string',
                'recommended_services' => ['slug_or_name'],
                'risk_flags' => ['string'],
                'suggested_next_step' => 'string',
                'caution_notes' => 'string',
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function serviceRecommendationPrompt(array $consultationData, array $services): string
    {
        return json_encode([
            'brand_tone' => 'warm, elegant, concise, premium skincare advisor',
            'constraints' => [
                'Informational only. Not medical advice.',
                'Choose recommendations only from allowed_services',
                'Avoid unsafe claims and certainty',
            ],
            'consultation_data' => $consultationData,
            'allowed_services' => $services,
            'required_json_shape' => [
                'recommended_services' => ['slug_or_name'],
                'explanation_summary' => 'string',
                'caution_notes' => 'string',
                'suggested_next_step' => 'string',
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function contentPrompt(array $input, string $type): string
    {
        return json_encode([
            'brand' => 'Skin by Noor',
            'style' => 'Elegant, warm, professional, concise, premium skincare business',
            'guardrails' => [
                'No medical diagnosis',
                'No cure claims',
                'No guaranteed outcomes',
            ],
            'task' => 'Generate a draft only. Human review required before publishing.',
            'type' => $type,
            'input' => $input,
            'required_json_shape' => [
                'title' => 'string|null',
                'body' => 'string',
                'cta' => 'string|null',
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
