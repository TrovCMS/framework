<?php

namespace Trov\Actions;

use Trov\Utils\ConsoleWriter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Trov\Concerns\CanInstallModule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Trov\Concerns\AbortsCommands;
use Trov\Concerns\CanModifyRoutes;

class InstallDiscoveries
{
    use CanInstallModule;
    use AbortsCommands;
    use CanModifyRoutes;

    private $consoleWriter;

    public $label = 'Discovery Center';

    public $slug = 'discovery';

    public $slugPlural = 'discoveries';

    public $tables = ['discovery_topics', 'discovery_articles'];

    public $resources = ['DiscoveryTopicResource', 'DiscoveryArticleResource'];

    public function __construct(ConsoleWriter $consoleWriter)
    {
        $this->consoleWriter = $consoleWriter;
    }

    public function __invoke($force = false)
    {
        if ($this->checkIfAlreadyInstalled() && ! $force) {
            $this->consoleWriter->newLine();
            $this->consoleWriter->warn('Seems you have already installed the ' . $this->label . ' module!');
            $this->consoleWriter->warn('You should run `trov:add ' . $this->slugPlural . ' --force` instead to refresh the the ' . $this->label . ' module tables and setup.');

            $confirmed = $this->consoleWriter->confirm('Run `trov:add ' . $this->slugPlural . ' --force` instead?', false);

            if ($confirmed) {
                $this->install(true);
            } else {
                $this->consoleWriter->warn('Adding ' . $this->label . ' module canceled.');
            }
            return 1;
        }

        $this->install($force);
    }

    protected function install(bool $force = false)
    {
        $this->consoleWriter->logStep('Installing ' . $this->label . ' module!');

        shell_exec('php artisan vendor:publish --tag=trov:' . $this->slugPlural . '-migrations' . ($force ? ' --force' : ''));

        $this->handleDatabase($force);

        shell_exec('php artisan vendor:publish --tag=trov:' . $this->slugPlural . ($force ? ' --force' : ''));

        $this->consoleWriter->success('Resources published.');

        shell_exec('php artisan shield:generate --resource=' . collect($this->resources)->implode(','));

        $this->consoleWriter->success('Policies published.');

        $this->addToRoutes([
            "Route::name('discovery-topics.show')->get('/discovery-center/topics/{topic:slug}/', [\\App\\Http\\Controllers\\DiscoveryTopicController::class, 'show']);",
            "Route::name('discovery-articles.show')->get('/discovery-center/articles/{article:slug}/', [\\App\\Http\\Controllers\\DiscoveryArticleController::class, 'show']);",
        ]);

        $this->consoleWriter->success('Routes published.');

        $this->consoleWriter->success('Trov ' . $this->label . ' module is now installed.');
    }
}
