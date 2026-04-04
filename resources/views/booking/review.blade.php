@extends('booking.layouts.app')
@section('content')
<div class="card">
<p><strong>Service:</strong> {{ $wizard['locale']==='fr' ? $wizard['service']->name_fr : $wizard['service']->name_en }}</p>
<p><strong>Date:</strong> {{ $wizard['appointment_date'] }}</p>
<p><strong>Time:</strong> {{ $wizard['start_time'] }}</p>
<p><strong>Client:</strong> {{ $wizard['customer']['first_name'] }} {{ $wizard['customer']['last_name'] ?? '' }}</p>
<p><strong>Price:</strong> {{ $wizard['currency']==='EUR' ? number_format((float)$wizard['service']->price_eur,2).' EUR' : number_format((float)$wizard['service']->price_tnd,2).' TND' }}</p>
<form method="POST" action="{{ route('booking.confirm') }}">@csrf
<label><input type="checkbox" name="confirm" value="1" required> I confirm this appointment.</label><br><br>
<button class="btn">{{ __('booking.confirm') }}</button>
</form>
</div>
@endsection
