<?php

namespace Trov\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait CanInstallModule
{
    public function getTables(): Collection
    {
        return collect($this->tables);
    }

    protected function checkIfAlreadyInstalled(): bool
    {
        $count = $this->getTables()
            ->filter(fn ($table): bool => Schema::hasTable($table))
            ->count();

        if ($count !== 0) {
            return true;
        }

        return false;
    }

    protected function handleDatabase(bool $force = false): void
    {
        if ($force) {
            try {
                Schema::disableForeignKeyConstraints();

                $this->getTables()->each(function ($table): void {
                    DB::table('migrations')->where('migration', 'like', '%_create_' . $table . '_table')->delete();
                    DB::statement('DROP TABLE IF EXISTS ' . $table);
                });

                Schema::enableForeignKeyConstraints();

                shell_exec('php artisan migrate');
                $this->consoleWriter->success('Database migrations refreshed.');
            } catch (\Throwable $e) {
                $this->consoleWriter->warn($e->getMessage());
            }
        } else {
            shell_exec('php artisan migrate');
            $this->consoleWriter->success('Database migrated.');
        }
    }
}
