@extends('admin.layouts.app')

@section('title', 'Website Settings')
@section('header', 'Website Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <section class="card">
        <h2>Branding</h2>
        <div class="grid">
            <div>
                <label>Site Name</label>
                <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" required>
                @error('site_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Site Tagline (FR)</label>
                <input type="text" name="site_tagline_fr" value="{{ old('site_tagline_fr', $settings->site_tagline_fr) }}">
            </div>
            <div>
                <label>Site Tagline (EN)</label>
                <input type="text" name="site_tagline_en" value="{{ old('site_tagline_en', $settings->site_tagline_en) }}">
            </div>
            <div>
                <label>Logo</label>
                <input type="file" name="logo" accept="image/*">
                @if($settings->logo_url)<p class="muted">Current: <a href="{{ $settings->logo_url }}" target="_blank">View logo</a></p>@endif
                @error('logo')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div>
                <label>Favicon</label>
                <input type="file" name="favicon" accept="image/*">
                @if($settings->favicon_url)<p class="muted">Current: <a href="{{ $settings->favicon_url }}" target="_blank">View favicon</a></p>@endif
                @error('favicon')<div class="error">{{ $message }}</div>@enderror
            </div>
        </div>
    </section>

    <section class="card">
        <h2>Contact Information</h2>
        <div class="grid">
            <div><label>Phone</label><input type="text" name="phone" value="{{ old('phone', $settings->phone) }}"></div>
            <div><label>WhatsApp Number</label><input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings->whatsapp_number) }}"></div>
            <div><label>Address (FR)</label><textarea name="address_fr">{{ old('address_fr', $settings->address_fr) }}</textarea></div>
            <div><label>Address (EN)</label><textarea name="address_en">{{ old('address_en', $settings->address_en) }}</textarea></div>
        </div>
    </section>

    <section class="card">
        <h2>Social Links</h2>
        <div class="grid">
            <div><label>Facebook URL</label><input type="url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}"></div>
            <div><label>Instagram URL</label><input type="url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}"></div>
            <div><label>TikTok URL</label><input type="url" name="tiktok_url" value="{{ old('tiktok_url', $settings->tiktok_url) }}"></div>
        </div>
    </section>

    <section class="card">
        <h2>Localization</h2>
        <div class="grid">
            <div>
                <label>Default Language</label>
                <select name="default_language">
                    <option value="fr" @selected(old('default_language', $settings->default_language)==='fr')>French</option>
                    <option value="en" @selected(old('default_language', $settings->default_language)==='en')>English</option>
                </select>
            </div>
            <div>
                <label>Supported Languages</label>
                <select name="supported_languages[]" multiple>
                    <option value="fr" @selected(in_array('fr', old('supported_languages', $settings->supported_languages ?? []), true))>French</option>
                    <option value="en" @selected(in_array('en', old('supported_languages', $settings->supported_languages ?? []), true))>English</option>
                </select>
            </div>
            <div>
                <label>Default Currency</label>
                <select name="default_currency">
                    <option value="TND" @selected(old('default_currency', $settings->default_currency)==='TND')>TND</option>
                    <option value="EUR" @selected(old('default_currency', $settings->default_currency)==='EUR')>EUR</option>
                </select>
            </div>
            <div>
                <label>Supported Currencies</label>
                <select name="supported_currencies[]" multiple>
                    <option value="TND" @selected(in_array('TND', old('supported_currencies', $settings->supported_currencies ?? []), true))>TND</option>
                    <option value="EUR" @selected(in_array('EUR', old('supported_currencies', $settings->supported_currencies ?? []), true))>EUR</option>
                </select>
            </div>
            <div>
                <label>Timezone</label>
                <input type="text" name="timezone" value="{{ old('timezone', $settings->timezone) }}" required>
            </div>
        </div>
    </section>

    <section class="card">
        <h2>Homepage Hero</h2>
        <div class="grid">
            <div><label>Hero Title (FR)</label><input type="text" name="hero_title_fr" value="{{ old('hero_title_fr', $settings->hero_title_fr) }}"></div>
            <div><label>Hero Title (EN)</label><input type="text" name="hero_title_en" value="{{ old('hero_title_en', $settings->hero_title_en) }}"></div>
            <div><label>Hero Subtitle (FR)</label><textarea name="hero_subtitle_fr">{{ old('hero_subtitle_fr', $settings->hero_subtitle_fr) }}</textarea></div>
            <div><label>Hero Subtitle (EN)</label><textarea name="hero_subtitle_en">{{ old('hero_subtitle_en', $settings->hero_subtitle_en) }}</textarea></div>
            <div><label>Hero Button Text (FR)</label><input type="text" name="hero_button_text_fr" value="{{ old('hero_button_text_fr', $settings->hero_button_text_fr) }}"></div>
            <div><label>Hero Button Text (EN)</label><input type="text" name="hero_button_text_en" value="{{ old('hero_button_text_en', $settings->hero_button_text_en) }}"></div>
        </div>
    </section>


    <section class="card">
        <h2>Contact Page Content</h2>
        <div class="grid">
            <div><label>Contact Title (FR)</label><input type="text" name="contact_page_title_fr" value="{{ old('contact_page_title_fr', $settings->contact_page_title_fr) }}"></div>
            <div><label>Contact Title (EN)</label><input type="text" name="contact_page_title_en" value="{{ old('contact_page_title_en', $settings->contact_page_title_en) }}"></div>
            <div><label>Contact Intro (FR)</label><textarea name="contact_intro_fr">{{ old('contact_intro_fr', $settings->contact_intro_fr) }}</textarea></div>
            <div><label>Contact Intro (EN)</label><textarea name="contact_intro_en">{{ old('contact_intro_en', $settings->contact_intro_en) }}</textarea></div>
            <div><label>Map Embed URL</label><input type="url" name="map_embed_url" value="{{ old('map_embed_url', $settings->map_embed_url) }}"></div>
            <div><label>Opening Hours (FR)</label><textarea name="opening_hours_fr">{{ old('opening_hours_fr', $settings->opening_hours_fr) }}</textarea></div>
            <div><label>Opening Hours (EN)</label><textarea name="opening_hours_en">{{ old('opening_hours_en', $settings->opening_hours_en) }}</textarea></div>
        </div>
    </section>

    <button class="btn" type="submit">Save Settings</button>
</form>
@endsection
