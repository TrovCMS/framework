<?php

namespace Trov\Commands;

use Illuminate\Console\Command;

class InstallDemoCommand extends Command
{
    public $signature = 'trov:demo {--force}';

    public $description = 'Seed TrovCMS application with demo data.';

    public function handle()
    {
        $this->newLine();
        $this->comment('Setting up auth...');
        $this->call('db:seed', ['--class' => 'DemoSeeder']);

        $this->info('TrovCMS demo data seeded! 👍');

        return Command::SUCCESS;
    }
}
