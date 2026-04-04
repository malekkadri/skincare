<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AiContentHelperRequest;
use App\Models\Setting;
use App\Services\AI\AIService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiContentHelperController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.ai-content-helper.index', [
            'result' => null,
            'settings' => Setting::current(),
            'input' => [
                'content_type' => 'homepage_copy',
                'language' => app()->getLocale(),
                'context' => null,
                'prompt' => null,
            ],
        ]);
    }

    public function generate(AiContentHelperRequest $request, AIService $aiService): View
    {
        $validated = $request->validated();
        $result = $aiService->generateContent([
            'language' => $validated['language'],
            'context' => $validated['context'] ?? null,
            'prompt' => $validated['prompt'],
        ], $validated['content_type'], $request->user()?->id);

        return view('admin.ai-content-helper.index', [
            'result' => $result,
            'input' => $validated,
            'settings' => Setting::current(),
        ]);
    }
}
