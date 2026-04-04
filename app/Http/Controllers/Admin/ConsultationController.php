<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateConsultationRequest;
use App\Models\Consultation;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function index(): View
    {
        return view('admin.consultations.index', [
            'consultations' => Consultation::query()->with('latestAiResult')->latest()->paginate(20),
        ]);
    }

    public function show(Consultation $consultation): View
    {
        return view('admin.consultations.show', [
            'consultation' => $consultation->load(['customer', 'aiResults' => fn ($query) => $query->latest('generated_at')]),
        ]);
    }

    public function update(UpdateConsultationRequest $request, Consultation $consultation): RedirectResponse
    {
        $consultation->update($request->validated());

        return back()->with('success', 'Consultation updated.');
    }
}
