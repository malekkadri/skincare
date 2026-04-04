<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ServiceRecommendationRequest;
use App\Models\ConsultationImage;
use App\Models\Service;
use App\Models\Setting;
use App\Services\AI\AIService;
use App\Services\AI\GrokSkinAnalysisService;
use App\Services\Seo\SeoService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class ServiceRecommenderController extends Controller
{
    public function __construct(protected SeoService $seoService)
    {
    }

    public function index(): View
    {
        return view('public.recommender.index', [
            'result' => null,
            'settings' => Setting::current(),
            'seo' => $this->seoService->forPage('services', route('recommender.index'), __('consultation.recommender_title')),
        ]);
    }

    public function recommend(ServiceRecommendationRequest $request, AIService $aiService, GrokSkinAnalysisService $analysisService): View
    {
        $payload = $request->validated();
        $serviceCatalog = $this->serviceCatalog($payload['preferred_language']);

        $result = $aiService->recommendServices($payload);
        $tempPaths = [];

        if (! empty($payload['face_images'])) {
            try {
                $images = collect($payload['face_images'])->values()->map(function ($file, $index) use (&$tempPaths) {
                    $path = $file->store('recommender-images', 'local');
                    $tempPaths[] = $path;

                    return new ConsultationImage([
                        'disk' => 'local',
                        'path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'original_name' => $file->getClientOriginalName(),
                        'sort_order' => $index,
                    ]);
                })->all();

                $analysis = $analysisService->analyze([
                    'flow' => 'service_recommender',
                    'preferred_language' => $payload['preferred_language'],
                    'skin_type' => Arr::get($payload, 'skin_type'),
                    'skin_sensitivity_level' => Arr::get($payload, 'skin_sensitivity_level'),
                    'main_concerns' => Arr::get($payload, 'main_concerns'),
                    'preferred_goals' => Arr::get($payload, 'preferred_goals'),
                ], $serviceCatalog, $images);

                $normalized = $analysis['normalized'];
                $result = [
                    'status' => 'success',
                    'provider' => $analysis['provider'],
                    'model' => $analysis['model'],
                    'explanation_summary' => $normalized['user_facing_summary'],
                    'caution_notes' => implode(', ', $normalized['caution_flags']),
                    'suggested_next_step' => $normalized['needs_human_review'] ? 'Manual review recommended before booking.' : 'You can proceed to booking.',
                    'recommended_services' => collect($normalized['recommended_services'])->pluck('slug')->values()->all(),
                    'normalized_result' => $normalized,
                ];
            } catch (Throwable $exception) {
                report($exception);
                $result['status'] = 'skipped';
                $result['caution_notes'] = 'Face image analysis is temporarily unavailable. Recommendations were generated from form answers only.';
                $result['suggested_next_step'] = 'Proceed with booking and request a manual consultation review.';
            } finally {
                foreach ($tempPaths as $path) {
                    Storage::disk('local')->delete($path);
                }
            }
        }

        $recommendedIds = collect($result['normalized_result']['recommended_services'] ?? [])->pluck('service_id')->filter()->all();
        $recommendedSlugs = collect($result['recommended_services'] ?? [])->map(fn ($item) => (string) $item)->all();

        $services = Service::query()
            ->active()
            ->where(function ($query) use ($recommendedSlugs, $recommendedIds): void {
                $query->whereIn('id', $recommendedIds)
                    ->orWhereIn('slug', $recommendedSlugs)
                    ->orWhereIn('name_fr', $recommendedSlugs)
                    ->orWhereIn('name_en', $recommendedSlugs);
            })
            ->ordered()
            ->get();

        return view('public.recommender.index', [
            'result' => $result,
            'recommendedServices' => $services,
            'submitted' => $payload,
            'settings' => Setting::current(),
            'seo' => $this->seoService->forPage('services', route('recommender.index'), __('consultation.recommender_title')),
        ]);
    }

    protected function serviceCatalog(string $language): array
    {
        return Service::query()
            ->active()
            ->with('category:id,name_fr,name_en')
            ->ordered()
            ->get()
            ->map(function (Service $service) use ($language): array {
                $isFr = $language === 'fr';

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
    }
}
