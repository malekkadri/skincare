<form method="GET" class="card" style="margin-bottom:1rem">
    <div class="grid">
        <div><label>Date Preset</label><select name="date_preset"><option value="">Last 7 Days</option>@foreach(['today'=>'Today','last_7_days'=>'Last 7 Days','this_month'=>'This Month','last_month'=>'Last Month','custom'=>'Custom'] as $v=>$l)<option value="{{ $v }}" @selected(($filters['date_preset'] ?? 'last_7_days')===$v)>{{ $l }}</option>@endforeach</select></div>
        <div><label>Date From</label><input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"></div>
        <div><label>Date To</label><input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"></div>
        <div><label>Language</label><select name="language"><option value="">All</option><option value="fr" @selected(($filters['language'] ?? '')==='fr')>FR</option><option value="en" @selected(($filters['language'] ?? '')==='en')>EN</option></select></div>
        <div><label>Currency</label><select name="currency"><option value="">All</option><option value="TND" @selected(($filters['currency'] ?? '')==='TND')>TND</option><option value="EUR" @selected(($filters['currency'] ?? '')==='EUR')>EUR</option></select></div>
        @isset($appointmentStatuses)
            <div><label>Appointment Status</label><select name="appointment_status"><option value="">All</option>@foreach($appointmentStatuses as $status)<option value="{{ $status }}" @selected(($filters['appointment_status'] ?? '')===$status)>{{ ucfirst(str_replace('_',' ', $status)) }}</option>@endforeach</select></div>
        @endisset
        @isset($consultationStatuses)
            <div><label>Consultation Status</label><select name="consultation_status"><option value="">All</option>@foreach($consultationStatuses as $status)<option value="{{ $status }}" @selected(($filters['consultation_status'] ?? '')===$status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
        @endisset
        @isset($whatsappStatuses)
            <div><label>WhatsApp Status</label><select name="whatsapp_status"><option value="">All</option>@foreach($whatsappStatuses as $status)<option value="{{ $status }}" @selected(($filters['whatsapp_status'] ?? '')===$status)>{{ ucfirst($status) }}</option>@endforeach</select></div>
        @endisset
        @isset($categories)
            <div><label>Category</label><select name="category_id"><option value="">All</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected((string)($filters['category_id'] ?? '')===(string)$category->id)>{{ $category->name_en }}</option>@endforeach</select></div>
        @endisset
        @isset($services)
            <div><label>Service</label><select name="service_id"><option value="">All</option>@foreach($services as $service)<option value="{{ $service->id }}" @selected((string)($filters['service_id'] ?? '')===(string)$service->id)>{{ $service->name_en }}</option>@endforeach</select></div>
        @endisset
        @isset($templateKeys)
            <div><label>Template Key</label><select name="template_key"><option value="">All</option>@foreach($templateKeys as $key)<option value="{{ $key }}" @selected(($filters['template_key'] ?? '')===$key)>{{ $key }}</option>@endforeach</select></div>
        @endisset
        @isset($automationSources)
            <div><label>Automation Source</label><select name="automation_source"><option value="">All</option>@foreach($automationSources as $source)<option value="{{ $source }}" @selected(($filters['automation_source'] ?? '')===$source)>{{ $source }}</option>@endforeach</select></div>
        @endisset
    </div>
    <div class="toolbar" style="margin-top:1rem">
        <button class="btn" type="submit">Apply Filters</button>
        <a class="btn btn-secondary" href="{{ url()->current() }}">Reset</a>
    </div>
</form>
