<?php

namespace Trov\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    public $signature = 'trov:install {--force}';

    public $description = 'Install TrovCMS into application.';

    public function handle()
    {
        $this->newLine();
        $this->comment('Migrating database...');
        $this->call('migrate' . $this->option('force') ? ':fresh' : '');

        $this->comment('Setting up auth...');
        $this->newLine();
        $this->call('shield:install', ['--fresh' => $this->option('force')]);

        $this->comment('Seeding database...');
        $this->newLine();
        $this->call('db:seed');

        $this->info('TrovCMS installed! Make something great! 👍');

        return Command::SUCCESS;
    }
}
