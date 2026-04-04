<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateConsultationRequest;
use App\Jobs\AnalyzeConsultationFaceImagesJob;
use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\ConsultationImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            'consultation' => $consultation->load([
                'customer',
                'images',
                'aiResults' => fn ($query) => $query->latest('generated_at'),
            ]),
        ]);
    }

    public function update(UpdateConsultationRequest $request, Consultation $consultation): RedirectResponse
    {
        $consultation->update($request->validated());

        return back()->with('success', 'Consultation updated.');
    }

    public function image(ConsultationImage $image): StreamedResponse
    {
        abort_unless(auth()->check() && auth()->user()->can('manage_consultations'), 403);

        abort_unless(Storage::disk($image->disk)->exists($image->path), 404);

        return response()->stream(function () use ($image): void {
            echo Storage::disk($image->disk)->get($image->path);
        }, 200, [
            'Content-Type' => $image->mime_type ?: 'application/octet-stream',
            'Cache-Control' => 'private, max-age=120',
            'Content-Disposition' => 'inline; filename="consultation-image-'.$image->id.'"',
        ]);
    }

    public function retryAnalysis(Consultation $consultation): RedirectResponse
    {
        $aiResult = ConsultationAiResult::query()->firstOrCreate(
            ['consultation_id' => $consultation->id],
            ['status' => 'pending', 'generated_at' => now()]
        );

        $aiResult->update(['status' => 'pending', 'error_message' => null]);

        AnalyzeConsultationFaceImagesJob::dispatch($consultation->id);

        return back()->with('success', 'AI analysis was queued for retry.');
    }
}
