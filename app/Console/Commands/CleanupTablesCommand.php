<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CleanupTablesCommand extends Command
{
    protected $signature = 'cleanup:tables';
    protected $description = 'Drop all non-core tables and remove their migrations';

    public function handle()
    {
        $coreTables = [
            'migrations',
            'users',
            'admins',
            'password_reset_tokens',
            'sessions',
            'cache',
            'cache_locks',
            'jobs',
            'job_batches',
            'failed_jobs',
            'personal_access_tokens',
            // Spatie Roles & Permissions tables
            'roles',
            'permissions',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
        ];

        // 1. Drop non-core tables
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();
        
        Schema::disableForeignKeyConstraints();
        foreach ($tables as $table) {
            if (!in_array($table, $coreTables)) {
                $this->info("Dropping table: {$table}");
                Schema::dropIfExists($table);
                
                // Remove from migrations table
                DB::table('migrations')->where('migration', 'like', '%create_' . $table . '_table%')->delete();
                DB::table('migrations')->where('migration', 'like', '%add_%_to_' . $table . '%')->delete();
                DB::table('migrations')->where('migration', 'like', '%create_' . str_replace('_table', '', $table) . '%')->delete();
                DB::table('migrations')->where('migration', 'like', '%modify_' . $table . '%')->delete();
            }
        }
        Schema::enableForeignKeyConstraints();

        // 2. Remove migration files
        $coreMigrationKeywords = [
            '0001_01_01_000000_create_users_table',
            '0001_01_01_000001_create_cache_table',
            '0001_01_01_000002_create_jobs_table',
            'create_permission_tables',
            'create_personal_access_tokens_table',
            '_create_admins_table',
        ];

        $migrationFiles = File::files(database_path('migrations'));
        foreach ($migrationFiles as $file) {
            $filename = $file->getFilename();
            $isCore = false;
            foreach ($coreMigrationKeywords as $keyword) {
                if (str_contains($filename, $keyword)) {
                    $isCore = true;
                    break;
                }
            }

            if (!$isCore) {
                $this->info("Deleting migration: {$filename}");
                File::delete($file->getPathname());
            }
        }

        $this->info('Cleanup completed successfully!');
    }
}
