<?php

namespace App\Http\Controllers\Public;

use App\Jobs\QueueConsultationAcknowledgementsJob;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\StoreConsultationRequest;
use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\Customer;
use App\Services\AI\AIService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function create(): View
    {
        return view('public.consultation.create');
    }

    public function store(StoreConsultationRequest $request, AIService $aiService): RedirectResponse
    {
        $data = $request->validated();
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

        $consultation = Consultation::query()->create($data + [
            'customer_id' => $customer->id,
            'status' => 'new',
        ]);

        $summaryResult = $aiService->summarizeConsultation($consultation);

        ConsultationAiResult::query()->create([
            'consultation_id' => $consultation->id,
            'provider' => $summaryResult['provider'] ?? null,
            'model' => $summaryResult['model'] ?? null,
            'summary_text' => $summaryResult['summary_text'] ?? null,
            'recommended_services_json' => $summaryResult['recommended_services'] ?? null,
            'risk_flags_json' => $summaryResult['risk_flags'] ?? null,
            'raw_response_json' => $summaryResult['raw_response'] ?? null,
            'status' => $summaryResult['status'] ?? 'skipped',
            'error_message' => $summaryResult['error_message'] ?? null,
            'generated_at' => now(),
        ]);

        QueueConsultationAcknowledgementsJob::dispatch();

        return redirect()->route('consultation.success', $consultation);
    }

    public function success(Consultation $consultation): View
    {
        return view('public.consultation.success', [
            'consultation' => $consultation->load('latestAiResult'),
        ]);
    }
}
