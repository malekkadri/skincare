<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCustomerProgressPhotoRequest;
use App\Http\Requests\Admin\UpdateCustomerProfileRequest;
use App\Models\Customer;
use App\Models\CustomerProgressPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $clients = Customer::query()
            ->withCount(['appointments', 'consultations', 'progressPhotos'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = trim((string) $request->string('q'));
                $query->where(function ($inner) use ($search): void {
                    $inner
                        ->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.clients.index', ['clients' => $clients]);
    }

    public function show(Customer $client): View
    {
        $client->load([
            'appointments' => fn ($query) => $query->with('service')->latest('appointment_date')->limit(20),
            'consultations' => fn ($query) => $query->latest()->limit(10),
            'progressPhotos' => fn ($query) => $query->latest('captured_on')->latest(),
        ]);

        return view('admin.clients.show', ['client' => $client]);
    }

    public function update(UpdateCustomerProfileRequest $request, Customer $client): RedirectResponse
    {
        $client->update($request->validated());

        return back()->with('success', 'Patient information updated.');
    }

    public function storePhoto(StoreCustomerProgressPhotoRequest $request, Customer $client): RedirectResponse
    {
        $file = $request->file('photo');
        $mime = (string) $file->getMimeType();
        $extension = match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg',
        };

        $path = 'client-progress/'.$client->id.'/'.Str::ulid()->toBase32().'.'.$extension;
        Storage::disk('local')->put($path, file_get_contents($file->getRealPath()), ['visibility' => 'private']);

        $client->progressPhotos()->create([
            'captured_on' => $request->input('captured_on') ?: null,
            'title' => $request->input('title') ?: null,
            'notes' => $request->input('notes') ?: null,
            'disk' => 'local',
            'path' => $path,
            'mime_type' => $mime,
            'size_bytes' => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
        ]);

        return back()->with('success', 'Progress photo uploaded.');
    }

    public function destroyPhoto(Customer $client, CustomerProgressPhoto $photo): RedirectResponse
    {
        abort_unless($photo->customer_id === $client->id, 404);

        Storage::disk($photo->disk)->delete($photo->path);
        $photo->delete();

        return back()->with('success', 'Progress photo removed.');
    }

    public function photo(CustomerProgressPhoto $photo): StreamedResponse
    {
        abort_unless(auth()->check() && auth()->user()->can('manage_appointments'), 403);
        abort_unless($photo->disk === 'local', 404);

        $disk = Storage::disk($photo->disk);
        abort_unless($disk->exists($photo->path), 404);

        $stream = $disk->readStream($photo->path);
        abort_unless(is_resource($stream), 404);

        return response()->stream(function () use ($stream): void {
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => $photo->mime_type ?: 'application/octet-stream',
            'Cache-Control' => 'private, no-store, max-age=0',
            'Content-Disposition' => 'inline; filename="client-photo-'.$photo->id.'.jpg"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
