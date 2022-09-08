<?php

namespace Trov\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\OutputStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Trov\Actions\InstallAirport;
use Trov\Actions\InstallBlog;
use Trov\Actions\InstallDiscoveries;
use Trov\Actions\InstallFaqs;
use Trov\Actions\InstallSheets;
use Trov\Utils\ConsoleWriter;

class AddModuleCommand extends Command
{
    public $signature = 'trov:add
        {--faqs : Install FAQs Module}
        {--blog : Install Blog Module}
        {--airport : Install Airport Module}
        {--sheets : Install Sheets Module (Unbranded Pages)}
        {--discoveries : Install Discovery Center Module}
        {--all : Install All Modules}
        {--force : Force install the module}
    ';

    public $description = 'Add a module to the TrovCMS application.';

    protected $consoleWriter;

    public function run(InputInterface $input, OutputInterface $output): int
    {
        $io = new OutputStyle($input, $output);

        app()->singleton(ConsoleWriter::class, function () use ($input, $output) {
            return new ConsoleWriter($input, $output);
        });

        app()->alias(ConsoleWriter::class, 'console-writer');

        return parent::run($input, $output);
    }

    public function handle()
    {
        $this->setConsoleWriter();

        if (count(array_filter($this->options())) < 2) {
            $this->handleInvalidModule();
        }

        if ($this->option('faqs') || $this->option('all')) {
            app(InstallFaqs::class)($this->option('force'));
        }

        if ($this->option('blog') || $this->option('all')) {
            app(InstallBlog::class)($this->option('force'));
        }

        if ($this->option('blog') || $this->option('all')) {
            app(InstallAirport::class)($this->option('force'));
        }

        if ($this->option('sheets') || $this->option('all')) {
            app(InstallSheets::class)($this->option('force'));
        }

        if ($this->option('discoveries') || $this->option('all')) {
            app(InstallDiscoveries::class)($this->option('force'));
        }

        return Command::SUCCESS;
    }

    protected function setConsoleWriter()
    {
        $this->consoleWriter = app(ConsoleWriter::class);
    }

    public function handleInvalidModule()
    {
        $this->consoleWriter->note('No modules selected. Nothing to do.');

        return Command::FAILURE;
    }
}
