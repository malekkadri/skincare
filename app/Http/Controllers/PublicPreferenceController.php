<?php

namespace App\Http\Controllers;

use App\Http\Requests\Public\SetCurrencyRequest;
use App\Http\Requests\Public\SetLocaleRequest;
use Illuminate\Http\RedirectResponse;

class PublicPreferenceController extends Controller
{
    public function locale(SetLocaleRequest $request): RedirectResponse
    {
        session(['locale' => $request->validated('locale')]);

        return back();
    }

    public function currency(SetCurrencyRequest $request): RedirectResponse
    {
        session(['currency' => $request->validated('currency')]);

        return back();
    }
}
