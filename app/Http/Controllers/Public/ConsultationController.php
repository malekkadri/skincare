<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreConsultationRequest;
use App\Jobs\AnalyzeConsultationFaceImagesJob;
use App\Jobs\QueueConsultationAcknowledgementsJob;
use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\Customer;
use App\Models\Setting;
use App\Services\AI\AIService;
use App\Services\ConsultationImageService;
use App\Services\Seo\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function __construct(protected SeoService $seoService)
    {
    }

    public function create(): View
    {
        return view('public.consultation.create', [
            'settings' => Setting::current(),
            'seo' => $this->seoService->forPage('contact', route('consultation.create'), __('consultation.title')),
        ]);
    }

    public function store(StoreConsultationRequest $request, AIService $aiService, ConsultationImageService $imageService): RedirectResponse
    {
        $data = $request->validated();

        /** @var Consultation $consultation */
        $consultation = DB::transaction(function () use ($data, $aiService, $imageService): Consultation {
            $customer = Customer::query()->firstOrCreate(
                ['phone' => $data['phone']],
                [
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'] ?? null,
                    'email' => $data['email'] ?? null,
                    'preferred_language' => $data['preferred_language'],
                    'preferred_currency' => session('currency', 'TND'),
                ]
            );

            $consultation = Consultation::query()->create(Arr::except($data, ['face_images']) + [
                'customer_id' => $customer->id,
                'status' => 'new',
            ]);

            $imageService->storeUploadedImages($consultation, $data['face_images'] ?? [], 'consultation');

            $summaryResult = $aiService->summarizeConsultation($consultation);

            ConsultationAiResult::query()->create([
                'consultation_id' => $consultation->id,
                'provider' => $summaryResult['provider'] ?? null,
                'model' => $summaryResult['model'] ?? null,
                'summary_text' => $summaryResult['summary_text'] ?? null,
                'recommended_services_json' => $summaryResult['recommended_services'] ?? null,
                'risk_flags_json' => $summaryResult['risk_flags'] ?? null,
                'raw_response_json' => $summaryResult['raw_response'] ?? null,
                'status' => ! empty($data['face_images']) ? 'pending' : ($summaryResult['status'] ?? 'skipped'),
                'error_message' => $summaryResult['error_message'] ?? null,
                'generated_at' => now(),
            ]);

            return $consultation;
        });

        if (! empty($data['face_images'])) {
            AnalyzeConsultationFaceImagesJob::dispatch($consultation->id);
        }

        QueueConsultationAcknowledgementsJob::dispatch();

        return redirect()->route('consultation.success', $consultation)->with('success', __('consultation.success_message'));
    }

    public function success(Consultation $consultation): View
    {
        return view('public.consultation.success', [
            'consultation' => $consultation->load(['latestAiResult', 'images']),
            'settings' => Setting::current(),
            'seo' => $this->seoService->forPage('contact', route('consultation.success', $consultation), __('consultation.success_title')),
        ]);
    }
}
