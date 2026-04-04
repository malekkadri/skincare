<?php

namespace App\Http\Controllers\Admin\Ops;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Ops\TriggerBackupRequest;
use App\Services\BackupService;
use Illuminate\Http\RedirectResponse;

class BackupController extends Controller
{
    public function store(TriggerBackupRequest $request, BackupService $backupService): RedirectResponse
    {
        $path = $backupService->runDatabaseExport($request->validated('prefix'));

        return back()->with('success', "Backup artifact created: {$path}");
    }
}
