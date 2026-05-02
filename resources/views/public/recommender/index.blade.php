@extends('public.layouts.app')

@section('title', __('consultation.recommender_title'))

@section('content')
<section class="page-section">
    <div class="page-hero reveal recommender-hero">
        <p class="section-kicker">AI Recommender</p>
        <h1 class="section-title">{{ __('consultation.recommender_title') }}</h1>
        <p class="muted">Receive a refined recommendation guided by your skin profile, sensitivity, goals, and a face image.</p>
        <div class="hero-quick-actions">
            <a class="btn btn-soft" href="{{ route('home') }}">Back to home</a>
            <a class="btn" href="{{ route('booking.service') }}">{{ __('consultation.book_now') }}</a>
        </div>
    </div>
</section>

<section class="page-section section-tight-top">
    <div class="card recommender-shell centered-card reveal">
        <form method="POST" action="{{ route('recommender.recommend') }}" class="grid grid-2" data-submit-once enctype="multipart/form-data">
            @csrf
            <div class="form-field">
                <label for="preferred_language">{{ __('consultation.preferred_language') }}</label>
                <select id="preferred_language" name="preferred_language">
                    <option value="fr" @selected(old('preferred_language', $submitted['preferred_language'] ?? app()->getLocale()) === 'fr')>FR</option>
                    <option value="en" @selected(old('preferred_language', $submitted['preferred_language'] ?? app()->getLocale()) === 'en')>EN</option>
                </select>
            </div>
            <div class="form-field"><label for="skin_type">{{ __('consultation.skin_type') }}</label><input id="skin_type" name="skin_type" placeholder="e.g. Oily, combination, dry" value="{{ old('skin_type', $submitted['skin_type'] ?? '') }}"></div>
            <div class="form-field"><label for="skin_sensitivity_level">{{ __('consultation.skin_sensitivity_level') }}</label><input id="skin_sensitivity_level" name="skin_sensitivity_level" placeholder="e.g. Mild, moderate, high" value="{{ old('skin_sensitivity_level', $submitted['skin_sensitivity_level'] ?? '') }}"></div>
            <div class="form-field"><label for="preferred_goals">{{ __('consultation.preferred_goals') }}</label><input id="preferred_goals" name="preferred_goals" placeholder="e.g. Brightening, acne balance, texture" value="{{ old('preferred_goals', $submitted['preferred_goals'] ?? '') }}"></div>
            <div class="form-field form-span-full">
                <label for="main_concerns">{{ __('consultation.main_concerns') }}</label>
                <small class="field-help">Share your key concerns, current routine, and any reactions for better recommendations.</small>
                <textarea id="main_concerns" name="main_concerns" required>{{ old('main_concerns', $submitted['main_concerns'] ?? '') }}</textarea>
            </div>

            <div class="form-field form-span-full">
                <label for="face_images_upload">Face image</label>
                <p class="field-help">Upload a photo or take a picture. The image is used only to generate better recommendations.</p>
                <div class="image-action-row" role="group" aria-label="Image source options">
                    <label class="btn btn-soft" for="face_images_upload">Upload image</label>
                    <label class="btn btn-soft" for="face_images_camera">Take picture</label>
                </div>
                <input id="face_images_upload" type="file" name="face_images[]" accept="image/jpeg,image/png,image/webp,image/heic,image/heif" class="sr-only-file" required>
                <input id="face_images_camera" type="file" accept="image/*" capture="environment" class="sr-only-file">
                <small class="field-help">Camera capture may not be supported on all desktop browsers; use Upload image if needed.</small>
                @error('face_images')<div class="error">{{ $message }}</div>@enderror
                @error('face_images.*')<div class="error">{{ $message }}</div>@enderror
                <div id="face-image-error" class="error" aria-live="polite"></div>
                <div id="face-image-preview" class="image-preview" hidden>
                    <img id="face-image-preview-img" src="" alt="Selected face image preview">
                    <div class="btn-row">
                        <button type="button" class="btn btn-soft" id="remove-face-image">Remove image</button>
                        <label class="btn btn-soft" for="face_images_camera">Retake image</label>
                    </div>
                </div>
            </div>

            <div class="form-span-full recommender-submit-row">
                <button class="btn" type="submit">{{ __('consultation.get_recommendations') }}</button>
            </div>
        </form>

        @if($result)
            <div class="card recommender-result centered-card reveal">
                @if(($result['status'] ?? '') === 'success')
                    <div class="soft-panel">
                        <h3>Skin summary</h3>
                        <p>{{ $result['explanation_summary'] ?? '—' }}</p>
                    </div>

                    @if(! empty($result['normalized_result']['visible_concerns']))
                        <div class="soft-panel">
                            <h3>Visible concerns</h3>
                            <ul>
                                @foreach($result['normalized_result']['visible_concerns'] as $concern)
                                    <li>{{ $concern['key'] }} ({{ number_format(($concern['confidence'] ?? 0) * 100, 0) }}%) - {{ $concern['severity'] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="soft-panel">
                        <h3>{{ __('consultation.recommended_services') }}</h3>
                        <ul>
                            @forelse(($recommendedServices ?? []) as $service)
                                <li>{{ $service->localized_name }}</li>
                            @empty
                                <li>{{ __('consultation.no_service_match') }}</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="soft-panel">
                        <h4>Confidence</h4>
                        <p>{{ isset($result['normalized_result']['confidence_score']) ? number_format($result['normalized_result']['confidence_score'] * 100, 0).'%' : '—' }}</p>
                        <h4>{{ __('consultation.caution_notes') }}</h4>
                        <p>{{ $result['caution_notes'] ?: '—' }}</p>
                    </div>
                @else
                    <div class="empty-state">{{ __('consultation.ai_unavailable') }}</div>
                @endif

                <div class="btn-row result-actions">
                    <a class="btn btn-soft" href="{{ route('home') }}">Back to home</a>
                    <a class="btn" href="{{ route('booking.service') }}">{{ __('consultation.book_now') }}</a>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
    .recommender-hero {
        background:
            radial-gradient(130% 160% at 0% 0%, rgba(234, 209, 184, .32) 0%, transparent 60%),
            linear-gradient(140deg, #fff8f1 0%, #ffffff 62%);
        border: 1px solid #efdfcf;
        box-shadow: 0 18px 40px rgba(93, 65, 40, .08);
    }

    .hero-quick-actions {
        display: flex;
        gap: .65rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .recommender-shell {
        max-width: 940px;
        border: 1px solid #eadbc9;
        background: linear-gradient(180deg, #fffdfb 0%, #fff 100%);
        box-shadow: 0 20px 42px rgba(93, 65, 40, .08);
    }

    .recommender-shell .form-field label {
        font-weight: 600;
    }

    .recommender-shell input,
    .recommender-shell select,
    .recommender-shell textarea {
        background: #fffcf9;
        border-radius: 14px;
        border-color: #e8d8c7;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .recommender-shell input:focus,
    .recommender-shell select:focus,
    .recommender-shell textarea:focus {
        border-color: #d1ad89;
        box-shadow: 0 0 0 3px rgba(185, 144, 104, .15);
        outline: none;
    }

    .recommender-submit-row { padding-top: .4rem; border-top: 1px dashed #eadac8; }
    .recommender-submit-row .btn { min-width: 230px; }
    .recommender-result { margin-top: 1.15rem; border: 1px solid #eadbc8; background: linear-gradient(180deg, #fffaf4 0%, #fff 100%); }
    .recommender-result .soft-panel { border: 1px solid #e8d7c4; border-radius: 16px; background: #fff; }
    .result-actions { margin-top: 1rem; justify-content: flex-end; }

    .sr-only-file { position:absolute; left:-9999px; width:1px; height:1px; overflow:hidden; }
    .image-action-row { display:flex; gap:.65rem; flex-wrap:wrap; margin:.35rem 0; }
    .image-preview { margin-top:.75rem; padding:.75rem; border:1px solid #ead9c7; border-radius:14px; background:#fff8f0; }
    .image-preview img { width:100%; max-width:280px; height:auto; border-radius:12px; display:block; margin-bottom:.75rem; }

    @media (max-width: 760px) {
        .hero-quick-actions .btn,
        .recommender-submit-row .btn,
        .result-actions .btn { width: 100%; }
        .result-actions { justify-content: stretch; }
    }
</style>

<script>
const uploadInput = document.getElementById('face_images_upload');
const cameraInput = document.getElementById('face_images_camera');
const previewWrapper = document.getElementById('face-image-preview');
const previewImg = document.getElementById('face-image-preview-img');
const removeBtn = document.getElementById('remove-face-image');
const errorBox = document.getElementById('face-image-error');
const submitForm = document.querySelector('form[data-submit-once]');

const allowedTypes = ['image/jpeg','image/png','image/webp','image/heic','image/heif'];
const maxBytes = 8 * 1024 * 1024;

function clearError() { errorBox.textContent = ''; }
function setError(message) { errorBox.textContent = message; }
function clearPreview() { previewImg.src = ''; previewWrapper.hidden = true; }

function setFileOnUploadInput(file) {
    const dt = new DataTransfer();
    dt.items.add(file);
    uploadInput.files = dt.files;
}

function handleFile(file) {
    clearError();
    if (!file) { clearPreview(); return; }
    if (!file.type.startsWith('image/')) { setError('Please choose an image file.'); clearPreview(); return; }
    if (!allowedTypes.includes(file.type)) { setError('Supported file types: jpg, jpeg, png, webp, and heic/heif when available.'); clearPreview(); return; }
    if (file.size > maxBytes) { setError('The image is too large. Please use a file smaller than 8MB.'); clearPreview(); return; }

    setFileOnUploadInput(file);
    previewImg.src = URL.createObjectURL(file);
    previewWrapper.hidden = false;
}

uploadInput?.addEventListener('change', () => handleFile(uploadInput.files?.[0]));
cameraInput?.addEventListener('change', () => {
    if (!cameraInput.files?.length) {
        setError('Camera capture was canceled or unavailable. You can still upload an image from your device.');
        return;
    }
    handleFile(cameraInput.files[0]);
});
removeBtn?.addEventListener('click', () => {
    uploadInput.value = '';
    cameraInput.value = '';
    clearPreview();
    clearError();
});

submitForm?.addEventListener('submit', (event) => {
    if (!uploadInput.files?.length) {
        event.preventDefault();
        setError('Please upload or take a picture before requesting recommendations.');
        return;
    }
    const button = submitForm.querySelector('button[type="submit"]');
    if (button) { button.disabled = true; button.textContent = @json(__('public.common.submitting')); }
}, { once: true });
</script>
@endsection
