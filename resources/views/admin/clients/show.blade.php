@extends('admin.layouts.app')

@section('title', 'Patient record')
@section('header', 'Patient record: '.$client->full_name)

@section('content')
<section class="card">
    <h2>Patient profile</h2>
    <form method="POST" action="{{ route('admin.clients.update', $client) }}">
        @csrf
        @method('PUT')

        <div class="grid">
            <div>
                <label>First Name</label>
                <input name="first_name" value="{{ old('first_name', $client->first_name) }}" required>
            </div>
            <div>
                <label>Last Name</label>
                <input name="last_name" value="{{ old('last_name', $client->last_name) }}">
            </div>
            <div>
                <label>Phone</label>
                <input name="phone" value="{{ old('phone', $client->phone) }}" required>
            </div>
            <div>
                <label>Email</label>
                <input name="email" value="{{ old('email', $client->email) }}" type="email">
            </div>
            <div>
                <label>Preferred Language</label>
                <select name="preferred_language">
                    <option value="fr" @selected(old('preferred_language', $client->preferred_language) === 'fr')>French</option>
                    <option value="en" @selected(old('preferred_language', $client->preferred_language) === 'en')>English</option>
                </select>
            </div>
            <div>
                <label>Preferred Currency</label>
                <select name="preferred_currency">
                    <option value="TND" @selected(old('preferred_currency', $client->preferred_currency) === 'TND')>TND</option>
                    <option value="EUR" @selected(old('preferred_currency', $client->preferred_currency) === 'EUR')>EUR</option>
                </select>
            </div>
            <div>
                <label>Allergies</label>
                <textarea name="allergies">{{ old('allergies', $client->allergies) }}</textarea>
            </div>
            <div>
                <label>Skin Notes</label>
                <textarea name="skin_notes">{{ old('skin_notes', $client->skin_notes) }}</textarea>
            </div>
            <div>
                <label>Medical Notes</label>
                <textarea name="medical_notes">{{ old('medical_notes', $client->medical_notes) }}</textarea>
            </div>
            <div>
                <label>General Notes</label>
                <textarea name="notes">{{ old('notes', $client->notes) }}</textarea>
            </div>
        </div>

        <div style="margin-top:1rem;">
            <button class="btn" type="submit">Save Profile</button>
        </div>
    </form>
</section>

<section class="card">
    <h2>Progress Photos</h2>
    <form method="POST" action="{{ route('admin.clients.photos.store', $client) }}" enctype="multipart/form-data">
        @csrf
        <div class="grid">
            <div>
                <label>Photo</label>
                <p class="muted">Optional. Recommended formats: PNG, JPG or WebP.</p>
                <input type="file" name="photo" accept="image/png,image/jpeg,image/webp">
            </div>
            <div>
                <label>Captured Date</label>
                <input type="date" name="captured_on" value="{{ old('captured_on') }}">
            </div>
            <div>
                <label>Title</label>
                <input name="title" value="{{ old('title') }}" placeholder="Before peel - week 1">
            </div>
            <div>
                <label>Notes</label>
                <textarea name="notes">{{ old('notes') }}</textarea>
            </div>
        </div>
        <div style="margin-top:1rem;">
            <button class="btn" type="submit">Upload photo</button>
        </div>
    </form>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;margin-top:1rem;">
        @forelse($client->progressPhotos as $photo)
            <article style="border:1px solid var(--border);border-radius:.7rem;padding:.7rem;background:#fff;">
                <a href="{{ route('admin.clients.photos.show', $photo) }}" target="_blank" style="display:block;">
                    <img src="{{ route('admin.clients.photos.show', $photo) }}" alt="Progress photo" style="width:100%;height:170px;object-fit:cover;border-radius:.5rem;">
                </a>
                <div style="margin-top:.55rem;">
                    <strong>{{ $photo->title ?: 'Untitled photo' }}</strong>
                    <div class="muted">{{ $photo->captured_on?->format('Y-m-d') ?: 'No date' }}</div>
                    <p style="margin:.45rem 0 .55rem;font-size:.86rem;">{{ $photo->notes ?: '-' }}</p>
                    <form method="POST" action="{{ route('admin.clients.photos.destroy', [$client, $photo]) }}" onsubmit="return confirm('Delete this photo?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit">Delete</button>
                    </form>
                </div>
            </article>
        @empty
            <p class="muted">No progress photos uploaded yet.</p>
        @endforelse
    </div>
</section>

<section class="card">
    <h2>Previous Treatments (Appointments)</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Date</th>
            <th>Service</th>
            <th>Status</th>
            <th>Price</th>
            <th>Notes</th>
        </tr>
        </thead>
        <tbody>
        @forelse($client->appointments as $appointment)
            <tr>
                <td>{{ $appointment->appointment_date?->format('Y-m-d') }}</td>
                <td>{{ $appointment->service_name_snapshot_en ?: $appointment->service?->name_en }}</td>
                <td><span class="status {{ $appointment->status_badge_class }}">{{ ucfirst(str_replace('_', ' ', $appointment->status)) }}</span></td>
                <td>{{ $appointment->display_price }}</td>
                <td>{{ $appointment->notes ?: '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="5">No appointments yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>

<section class="card">
    <h2>Consultation History</h2>
    <table class="table">
        <thead>
        <tr>
            <th>Date</th>
            <th>Main Concerns</th>
            <th>Allergies</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($client->consultations as $consultation)
            <tr>
                <td>{{ $consultation->created_at?->format('Y-m-d') }}</td>
                <td>{{ \Illuminate\Support\Str::limit($consultation->main_concerns, 90) ?: '-' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($consultation->allergies, 60) ?: '-' }}</td>
                <td>{{ ucfirst($consultation->status) }}</td>
                <td><a class="btn btn-secondary" href="{{ route('admin.consultations.show', $consultation) }}">Open consultation</a></td>
            </tr>
        @empty
            <tr><td colspan="5">No consultation records.</td></tr>
        @endforelse
        </tbody>
    </table>
</section>
@endsection
