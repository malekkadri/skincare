<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlockedDateRequest;
use App\Models\BlockedDate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlockedDateController extends Controller
{
    public function index(): View
    {
        return view('admin.blocked-dates.index', [
            'blockedDates' => BlockedDate::query()->orderBy('blocked_date')->paginate(20),
        ]);
    }

    public function store(StoreBlockedDateRequest $request): RedirectResponse
    {
        BlockedDate::query()->create($request->validated());

        return back()->with('success', 'Blocked date added.');
    }

    public function destroy(BlockedDate $blockedDate): RedirectResponse
    {
        $blockedDate->delete();

        return back()->with('success', 'Blocked date removed.');
    }
}
