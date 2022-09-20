<?php

namespace Trov;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Trov\Commands\AddModuleCommand;
use Trov\Commands\InstallCommand;

class TrovServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('trov')
            ->hasViews()
            ->hasCommands([
                InstallCommand::class,
                AddModuleCommand::class,
            ]);
    }

    public function register(): void
    {
        parent::register();

        foreach (glob(__DIR__ . '/Helpers/*.php') as $file) {
            require_once $file;
        }
    }

    public function packageBooted(): void
    {
        $now = \Carbon\Carbon::now();

        parent::packageBooted();

        /**
         * FAQs Module
         */
        $this->publishes([
            __DIR__ . '/../stubs/faqs/resources/views' => base_path('resources/views/faqs'),
            __DIR__ . '/../stubs/faqs/database/factories' => database_path('factories'),
            __DIR__ . '/../stubs/faqs/database/seeders' => database_path('seeders'),
            __DIR__ . '/../stubs/faqs/app/Models' => app_path('Models'),
            __DIR__ . '/../stubs/faqs/resources/lang' => lang_path('trov/faqs'),
            __DIR__ . '/../stubs/faqs/app/Http/Controllers' => app_path('Http/Controllers'),
            __DIR__ . '/../stubs/faqs/app/Filament/Resources' => app_path('Filament/Resources'),
        ], 'trov:faqs');

        $this->publishes([
            __DIR__ . '/../stubs/faqs/database/migrations/create_faqs_table.php' => $this->generateMigrationName('create_faqs_table', $now->addSecond()),
        ], 'trov:faqs-migrations');

        /**
         * Blog Module
         */
        $this->publishes([
            __DIR__ . '/../stubs/blog/resources/views' => base_path('resources/views/blog'),
            __DIR__ . '/../stubs/blog/database/factories' => database_path('factories'),
            __DIR__ . '/../stubs/blog/database/seeders' => database_path('seeders'),
            __DIR__ . '/../stubs/blog/app/Models' => app_path('Models'),
            __DIR__ . '/../stubs/blog/resources/lang' => lang_path('trov/blog'),
            __DIR__ . '/../stubs/blog/app/Http/Controllers' => app_path('Http/Controllers'),
            __DIR__ . '/../stubs/blog/app/Filament/Resources' => app_path('Filament/Resources'),
        ], 'trov:blog');

        $this->publishes([
            __DIR__ . '/../stubs/blog/database/migrations/create_posts_table.php' => $this->generateMigrationName('create_posts_table', $now->addSecond()),
        ], 'trov:blog-migrations');

        /**
         * Airport Module
         */
        $this->publishes([
            __DIR__ . '/../stubs/airport/resources/views' => base_path('resources/views/airport'),
            __DIR__ . '/../stubs/airport/database/factories' => database_path('factories'),
            __DIR__ . '/../stubs/airport/database/seeders' => database_path('seeders'),
            __DIR__ . '/../stubs/airport/app/Models' => app_path('Models'),
            __DIR__ . '/../stubs/airport/resources/lang' => lang_path('trov/airport'),
            __DIR__ . '/../stubs/airport/app/Http/Controllers' => app_path('Http/Controllers'),
            __DIR__ . '/../stubs/airport/app/Filament/Resources' => app_path('Filament/Resources'),
        ], 'trov:airport');

        $this->publishes([
            __DIR__ . '/../stubs/airport/database/migrations/create_runways_table.php' => $this->generateMigrationName('create_runways_table', $now->addSecond()),
        ], 'trov:airport-migrations');

        /**
         * Sheets Module
         */
        $this->publishes([
            __DIR__ . '/../stubs/sheets/resources/views' => base_path('resources/views/sheets'),
            __DIR__ . '/../stubs/sheets/database/factories' => database_path('factories'),
            __DIR__ . '/../stubs/sheets/database/seeders' => database_path('seeders'),
            __DIR__ . '/../stubs/sheets/app/Models' => app_path('Models'),
            __DIR__ . '/../stubs/sheets/resources/lang' => lang_path('trov/sheets'),
            __DIR__ . '/../stubs/sheets/app/Http/Controllers' => app_path('Http/Controllers'),
            __DIR__ . '/../stubs/sheets/app/Filament/Resources' => app_path('Filament/Resources'),
        ], 'trov:sheets');

        $this->publishes([
            __DIR__ . '/../stubs/sheets/database/migrations/create_sheets_table.php' => $this->generateMigrationName('create_sheets_table', $now->addSecond()),
        ], 'trov:sheets-migrations');

        /**
         * Discoveries Module
         */
        $this->publishes([
            __DIR__ . '/../stubs/discoveries/resources/views' => base_path('resources/views/discoveries'),
            __DIR__ . '/../stubs/discoveries/database/factories' => database_path('factories'),
            __DIR__ . '/../stubs/discoveries/database/seeders' => database_path('seeders'),
            __DIR__ . '/../stubs/discoveries/app/Models' => app_path('Models'),
            __DIR__ . '/../stubs/discoveries/resources/lang' => lang_path('trov/discoveries'),
            __DIR__ . '/../stubs/discoveries/app/Http/Controllers' => app_path('Http/Controllers'),
            __DIR__ . '/../stubs/discoveries/app/Filament/Resources' => app_path('Filament/Resources'),
            __DIR__ . '/../stubs/discoveries/app/View' => app_path('View'),
            __DIR__ . '/../stubs/discoveries/app/Forms' => app_path('Forms'),
        ], 'trov:discoveries');

        $this->publishes([
            __DIR__ . '/../stubs/discoveries/database/migrations/create_discovery_topics_table.php' => $this->generateMigrationName('create_discovery_topics_tables', $now->addSecond()),
            __DIR__ . '/../stubs/discoveries/database/migrations/create_discovery_articles_table.php' => $this->generateMigrationName('create_discovery_articles_tables', $now->addSecond()),
        ], 'trov:discoveries-migrations');
    }
}
