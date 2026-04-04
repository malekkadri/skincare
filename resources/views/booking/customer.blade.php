@extends('booking.layouts.app')
@section('content')
<div class="card"><form method="POST" action="{{ route('booking.customer.save') }}">@csrf
<div class="grid grid-2">
<div><label>First name</label><input name="first_name" value="{{ old('first_name', $wizard['customer']['first_name'] ?? '') }}" required></div>
<div><label>Last name</label><input name="last_name" value="{{ old('last_name', $wizard['customer']['last_name'] ?? '') }}"></div>
<div><label>Phone</label><input name="phone" value="{{ old('phone', $wizard['customer']['phone'] ?? '') }}" required></div>
<div><label>Email</label><input name="email" type="email" value="{{ old('email', $wizard['customer']['email'] ?? '') }}"></div>
<div><label>Language</label><select name="preferred_language"><option value="fr" @selected(old('preferred_language', $wizard['customer']['preferred_language'] ?? app()->getLocale())==='fr')>Français</option><option value="en" @selected(old('preferred_language', $wizard['customer']['preferred_language'] ?? app()->getLocale())==='en')>English</option></select></div>
<div><label>Notes</label><textarea name="notes">{{ old('notes', $wizard['customer']['notes'] ?? '') }}</textarea></div>
</div><button class="btn" style="margin-top:1rem">{{ __('booking.continue') }}</button></form></div>
@endsection
