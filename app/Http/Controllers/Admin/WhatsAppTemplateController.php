<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateWhatsAppTemplateRequest;
use App\Models\WhatsAppTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WhatsAppTemplateController extends Controller
{
    public function index(): View
    {
        $templates = WhatsAppTemplate::query()->orderBy('key')->orderBy('language')->get();

        return view('admin.whatsapp.templates', [
            'templates' => $templates,
            'keys' => WhatsAppTemplate::KEYS,
            'languages' => ['fr', 'en'],
        ]);
    }

    public function update(UpdateWhatsAppTemplateRequest $request, WhatsAppTemplate $template): RedirectResponse
    {
        $template->update([
            'message_body' => $request->validated('message_body'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Template updated.');
    }
}
