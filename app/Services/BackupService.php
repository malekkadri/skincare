<?php

namespace App\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    public function runDatabaseExport(string $filenamePrefix = 'database'): string
    {
        $timestamp = now()->format('Ymd_His');
        $path = "backups/{$filenamePrefix}_{$timestamp}.sql";

        $dump = '-- Module 9 backup placeholder generated at '.now()->toDateTimeString().PHP_EOL;
        $dump .= '-- Replace this with your managed backup integration in production.'.PHP_EOL;
        $dump .= '-- DB connection: '.config('database.default').PHP_EOL;

        Storage::disk('local')->put($path, $dump);

        return $path;
    }

    public function listBackups(): array
    {
        return collect(Storage::disk('local')->files('backups'))
            ->map(fn (string $file) => [
                'path' => $file,
                'size' => Storage::disk('local')->size($file),
                'modified_at' => now()->createFromTimestamp(Storage::disk('local')->lastModified($file)),
            ])
            ->sortByDesc('modified_at')
            ->values()
            ->all();
    }

    public function queueCachesRefresh(): void
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
    }
}
