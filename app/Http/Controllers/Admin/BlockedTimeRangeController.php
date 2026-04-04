<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlockedTimeRangeRequest;
use App\Models\BlockedTimeRange;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlockedTimeRangeController extends Controller
{
    public function index(): View
    {
        return view('admin.blocked-times.index', [
            'blockedTimes' => BlockedTimeRange::query()->orderBy('blocked_date')->orderBy('start_time')->paginate(20),
        ]);
    }

    public function store(StoreBlockedTimeRangeRequest $request): RedirectResponse
    {
        BlockedTimeRange::query()->create($request->validated());

        return back()->with('success', 'Blocked time range added.');
    }

    public function destroy(BlockedTimeRange $blockedTime): RedirectResponse
    {
        $blockedTime->delete();

        return back()->with('success', 'Blocked time range removed.');
    }
}
