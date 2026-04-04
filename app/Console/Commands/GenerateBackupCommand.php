<?php

namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;

class GenerateBackupCommand extends Command
{
    protected $signature = 'ops:backup {--prefix=database : Backup filename prefix}';

    protected $description = 'Generate a safe backup artifact placeholder in storage/app/backups';

    public function handle(BackupService $backupService): int
    {
        $path = $backupService->runDatabaseExport((string) $this->option('prefix'));

        $this->info("Backup artifact generated: {$path}");

        return self::SUCCESS;
    }
}
