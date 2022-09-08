<?php

namespace Trov\Actions;

use Trov\Concerns\AbortsCommands;
use Trov\Concerns\CanInstallModule;
use Trov\Concerns\CanModifyRoutes;
use Trov\Utils\ConsoleWriter;

class InstallBlog
{
    use CanInstallModule;
    use AbortsCommands;
    use CanModifyRoutes;

    private $consoleWriter;

    public $label = 'Blog';

    public $slug = 'blog';

    public $slugPlural = 'blog';

    public $tables = ['posts'];

    public $resources = ['PostResource'];

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
            "Route::name('blog.index')->get('/blog/', [\\App\\Http\\Controllers\\PostController::class, 'index']);",
            "Route::name('blog.show')->get('/posts/{post:slug}/', [\\App\\Http\\Controllers\\PostController::class, 'show']);",
        ]);

        $this->consoleWriter->success('Routes published.');

        $this->consoleWriter->success('Trov ' . $this->label . ' module is now installed.');
    }
}
