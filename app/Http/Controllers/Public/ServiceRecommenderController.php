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

        $recommendedKeys = collect($result['recommended_services'] ?? [])->map(fn ($item) => (string) $item)->all();

        $services = Service::query()
            ->active()
            ->where(function ($query) use ($recommendedKeys): void {
                $query->whereIn('slug', $recommendedKeys)
                    ->orWhereIn('name_fr', $recommendedKeys)
                    ->orWhereIn('name_en', $recommendedKeys);
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
