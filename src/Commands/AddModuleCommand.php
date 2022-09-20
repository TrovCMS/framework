<?php

namespace Trov\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Trov\Concerns\CanModifyRoutes;

class AddModuleCommand extends Command
{
    use CanModifyRoutes;

    public $signature = 'trov:add
        {--force : Force install the module}
    ';

    public $description = 'Add a module to the TrovCMS application.';

    public function handle()
    {
        $modules = $this->choice(
            'What modules would you like to add?',
            ['All', 'FAQs', 'Blog', 'Airport (Landing Pages)', 'Sheets (Unbranded Pages)', 'Discovery Center (Topics and Articles)'],
            0,
            null,
            true
        );

        $addAllModules = in_array('All', $modules);

        if ($addAllModules || in_array('FAQs', $modules)) {
            $this->installFaqsModule();
        }

        if ($addAllModules || in_array('Blog', $modules)) {
            $this->installBlogModule();
        }

        if ($addAllModules || in_array('Airport (Landing Pages)', $modules)) {
            $this->installAirportModule();
        }

        if ($addAllModules || in_array('Sheets (Unbranded Pages)', $modules)) {
            $this->installSheetsModule();
        }

        if ($addAllModules || in_array('Discovery Center (Topics and Articles)', $modules)) {
            $this->installDiscoveriesModule();
        }

        return Command::SUCCESS;
    }

    private function installFaqsModule(): void
    {
        $this->handleModuleInstall([
            'label' => 'FAQs',
            'slug' => 'faq',
            'slugPlural' => 'faqs',
            'tables' => ['faqs'],
            'resources' => ['FaqResource'],
            'routes' => [
                "Route::name('faqs.index')->get('/faqs/', [\\App\\Http\\Controllers\\FaqController::class, 'index']);",
                "Route::name('faqs.show')->get('/faqs/{faq:slug}/', [\\App\\Http\\Controllers\\FaqController::class, 'show']);",
            ],
        ]);
    }

    private function installBlogModule(): void
    {
        $this->handleModuleInstall([
            'label' => 'Blog',
            'slug' => 'blog',
            'slugPlural' => 'blog',
            'tables' => ['posts'],
            'resources' => ['PostResource'],
            'routes' => [
                "Route::name('blog.index')->get('/blog/', [\\App\\Http\\Controllers\\PostController::class, 'index']);",
                "Route::name('blog.show')->get('/posts/{post:slug}/', [\\App\\Http\\Controllers\\PostController::class, 'show']);",
            ],
        ]);
    }

    private function installAirportModule(): void
    {
        $this->handleModuleInstall([
            'label' => 'Airport',
            'slug' => 'airport',
            'slugPlural' => 'airport',
            'tables' => ['runways'],
            'resources' => ['RunwayResource'],
            'routes' => [
                "Route::name('airport.show')->get('/airport/{runway:slug}/', [\\App\\Http\\Controllers\\AirportController::class, 'show']);",
            ],
        ]);
    }

    private function installSheetsModule(): void
    {
        $this->handleModuleInstall([
            'label' => 'Sheets',
            'slug' => 'sheet',
            'slugPlural' => 'sheets',
            'tables' => ['sheets'],
            'resources' => ['SheetResource'],
            'routes' => [
                "Route::name('sheets.show')->get('/{type}s/{page:slug}/', [\\App\\Http\\Controllers\\SheetController::class, 'show']);",
            ],
        ]);
    }

    private function installDiscoveriesModule(): void
    {
        $this->handleModuleInstall([
            'label' => 'Discovery Center',
            'slug' => 'discovery',
            'slugPlural' => 'discoveries',
            'tables' => ['discovery_topics', 'discovery_articles'],
            'resources' => ['DiscoveryTopicResource', 'DiscoveryArticleResource'],
            'routes' => [
                "Route::name('discoveries')->get('/discovery-center/', [\\App\\Http\\Controllers\\DiscoveryCenterController::class, 'index']);",
                "Route::name('discovery-topics.show')->get('/discovery-center/topics/{topic:slug}/', [\\App\\Http\\Controllers\\DiscoveryTopicController::class, 'show']);",
                "Route::name('discovery-articles.show')->get('/discovery-center/articles/{article:slug}/', [\\App\\Http\\Controllers\\DiscoveryArticleController::class, 'show']);",
            ],
        ]);
    }

    private function handleModuleInstall(array $options): int
    {
        if ($this->checkIfAlreadyInstalled($options['tables']) && ! $this->option('force')) {
            $this->newLine();
            $this->warn('Seems you have already installed the ' . $options['label'] . ' module!');

            $confirmed = $this->confirm('Would you like to force install the module instead?', false);

            if ($confirmed) {
                $this->installModule($options, true);
            } else {
                $this->warn('Adding ' . $options['label'] . ' module canceled.');
            }

            return Command::FAILURE;
        }

        $this->installModule($options, $this->option('force'));

        return Command::SUCCESS;
    }

    protected function checkIfAlreadyInstalled(array $tables): bool
    {
        $count = collect($tables)
            ->filter(fn ($table): bool => Schema::hasTable($table))
            ->count();

        if ($count !== 0) {
            return true;
        }

        return false;
    }

    protected function installModule(array $options, bool $force = false): void
    {
        $this->info('Installing ' . $options['label'] . ' module.');

        $this->callSilently('vendor:publish', [
            '--tag' => 'trov:' . $options['slugPlural'] . '-migrations',
            '--force' => $force,
        ]);

        if ($force) {
            try {
                Schema::disableForeignKeyConstraints();

                collect($options['tables'])->each(function ($table): void {
                    DB::table('migrations')->where('migration', 'like', '%_create_' . $table . '_table')->delete();
                    DB::statement('DROP TABLE IF EXISTS ' . $table);
                });

                Schema::enableForeignKeyConstraints();

                $this->comment('Refreshing database...');
                $this->callSilently('migrate');
            } catch (\Throwable $e) {
                $this->warn($e->getMessage());
            }
        } else {
            $this->comment('Migrating database...');
            $this->callSilently('migrate');
        }

        $this->comment('Publishing resources...');
        $this->callSilently('vendor:publish', [
            '--tag' => 'trov:' . $options['slugPlural'],
            '--force' => $force,
        ]);

        $this->comment('Creating policies...');
        $this->callSilently('shield:generate', [
            '--resource' => collect($options['resources'])->implode(','),
        ]);

        $this->comment('Publishing routes...');
        $this->addToRoutes($options['routes']);

        $this->info('Trov ' . $options['label'] . ' module is now installed.');
        $this->newLine();
    }
}
