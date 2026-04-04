<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAiSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AiSettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.ai-settings.edit', [
            'settings' => Setting::current(),
        ]);
    }

    public function update(UpdateAiSettingsRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $settings = Setting::current();

        foreach (['ai_enabled', 'ai_enable_consultation_summary', 'ai_enable_service_recommendation', 'ai_enable_admin_content_helper'] as $field) {
            $data[$field] = $request->boolean($field);
        }

        $settings->update($data);

        return back()->with('success', 'AI settings updated successfully.');
    }
}
