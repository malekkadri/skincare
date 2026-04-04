<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateWhatsAppSettingsRequest;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WhatsAppSettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.whatsapp.settings', ['settings' => Setting::current()]);
    }

    public function update(UpdateWhatsAppSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $settings = Setting::current();

        foreach ([
            'whatsapp_enabled',
            'send_booking_confirmation_whatsapp',
            'send_booking_cancellation_whatsapp',
            'send_booking_reschedule_whatsapp',
            'whatsapp_automation_enabled',
            'send_24h_reminder',
            'send_2h_reminder',
            'send_post_appointment_followup',
            'send_consultation_acknowledgement',
        ] as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        if (empty($validated['whatsapp_api_key'])) {
            unset($validated['whatsapp_api_key']);
        }

        if (! empty($validated['automation_pause_until'])) {
            $validated['automation_pause_until'] = Carbon::parse($validated['automation_pause_until'], 'Africa/Tunis');
        }

        $settings->update($validated);

        return back()->with('success', 'WhatsApp settings updated successfully.');
    }
}
