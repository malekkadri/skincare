<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\ServiceRecommendationRequest;
use App\Models\Service;
use App\Models\Setting;
use App\Services\AI\AIService;
use App\Services\Seo\SeoService;
use Illuminate\View\View;

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

    public function recommend(ServiceRecommendationRequest $request, AIService $aiService): View
    {
        $payload = $request->validated();
        $result = $aiService->recommendServices($payload);

        if (($result['status'] ?? null) === 'success') {
            $result['caution_notes'] = $result['caution_notes']
                ?? (! empty($payload['face_images'])
                    ? 'Face image analysis is not enabled in Groq-only mode. Recommendations were generated from form answers only.'
                    : null);
            $result['suggested_next_step'] = $result['suggested_next_step']
                ?? 'Proceed with booking and request a manual consultation review if needed.';
        }

        $recommendedSlugs = collect($result['recommended_services'] ?? [])->map(fn ($item) => (string) $item)->all();

        $services = Service::query()
            ->active()
            ->where(function ($query) use ($recommendedSlugs): void {
                $query->whereIn('slug', $recommendedSlugs)
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
}
