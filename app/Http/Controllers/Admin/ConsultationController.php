<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateConsultationRequest;
use App\Jobs\AnalyzeConsultationFaceImagesJob;
use App\Models\Consultation;
use App\Models\ConsultationAiResult;
use App\Models\ConsultationImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
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
        abort_unless($image->disk === 'local', 404);

        $disk = Storage::disk($image->disk);
        abort_unless($disk->exists($image->path), 404);

        $stream = $disk->readStream($image->path);
        abort_unless(is_resource($stream), 404);

        return response()->stream(function () use ($stream): void {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $image->mime_type ?: 'application/octet-stream',
            'Cache-Control' => 'private, no-store, max-age=0',
            'Content-Disposition' => 'inline; filename="consultation-image-'.$image->id.'.jpg"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function retryAnalysis(Consultation $consultation): RedirectResponse
    {
        if ($consultation->images()->doesntExist()) {
            return back()->with('error', 'No face images found for this consultation.');
        }

        $queued = true;

        $aiResult = DB::transaction(function () use ($consultation, &$queued): ConsultationAiResult {
            $result = ConsultationAiResult::query()->lockForUpdate()->firstOrCreate(
                ['consultation_id' => $consultation->id],
                ['status' => 'pending', 'generated_at' => now()]
            );

            if (in_array($result->status, ['pending', 'processing'], true)) {
                $queued = false;

                return $result;
            }

            $result->update(['status' => 'pending', 'error_message' => null]);

            return $result;
        });

        if (! $queued) {
            return back()->with('success', 'AI analysis is already queued or running.');
        }

        AnalyzeConsultationFaceImagesJob::dispatch($consultation->id);

        return back()->with('success', 'AI analysis was queued for retry.');
    }
}
