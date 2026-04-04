<?php

namespace App\Jobs;

use App\Models\AiUsageLog;
use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\Service;
use App\Services\AI\GrokSkinAnalysisService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Throwable;

class AnalyzeConsultationFaceImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public int $consultationId)
    {
    }

    public function backoff(): array
    {
        return [60, 300, 900];
    }

    public function handle(GrokSkinAnalysisService $analysisService): void
    {
        $consultation = Consultation::query()
            ->with(['images', 'latestAiResult'])
            ->findOrFail($this->consultationId);

        $aiResult = ConsultationAiResult::query()->firstOrCreate(
            ['consultation_id' => $consultation->id],
            ['status' => 'pending', 'generated_at' => now()]
        );

        $aiResult->update(['status' => 'processing']);

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

        try {
            $analysis = $analysisService->analyze([
                'flow' => 'consultation',
                'preferred_language' => $consultation->preferred_language,
                'skin_type' => $consultation->skin_type,
                'skin_sensitivity_level' => $consultation->skin_sensitivity_level,
                'main_concerns' => $consultation->main_concerns,
                'preferred_goals' => $consultation->preferred_goals,
                'additional_notes' => $consultation->additional_notes,
            ], $services, $consultation->images->all());

            DB::transaction(function () use ($analysis, $aiResult, $consultation): void {
                $normalized = $analysis['normalized'];

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
                    'generated_at' => now(),
                    'processed_at' => now(),
                ]);

                AiUsageLog::query()->create([
                    'feature_key' => 'consultation_face_analysis',
                    'provider' => $analysis['provider'],
                    'model' => $analysis['model'],
                    'status' => 'success',
                    'related_consultation_id' => $consultation->id,
                    'input_context_summary' => 'Consultation face image analysis',
                    'output_summary' => mb_substr((string) ($normalized['admin_summary'] ?: $normalized['user_facing_summary']), 0, 500),
                ]);
            });
        } catch (Throwable $exception) {
            $aiResult->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'processed_at' => now(),
            ]);

            AiUsageLog::query()->create([
                'feature_key' => 'consultation_face_analysis',
                'provider' => 'xai',
                'model' => config('services.xai.vision_model'),
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'related_consultation_id' => $consultation->id,
                'input_context_summary' => 'Consultation face image analysis',
            ]);

            throw $exception;
        }
    }
}
