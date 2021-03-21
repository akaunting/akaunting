<?php

namespace ConsoleTVs\Charts\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;

class ChartsCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:chart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new chart';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Chart';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/chart.stub';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('[Charts] Creating chart...');

        $this->handleParentMethodCall();

        $name = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($name);

        $this->info("[Charts] Chart created! - Location: {$path}");
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return str_replace(
            ['Library'],
            [$this->argument('library') ? ucfirst($this->argument('library')) : ucfirst(config('charts.default_library'))],
            $stub
        );
    }

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Charts';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the chart file'],

            ['library', InputArgument::OPTIONAL, 'Library of the chart'],
        ];
    }

    /**
     * Calls the right parent method depending on laravel version.
     * Adds support for Laravel v5.4.
     *
     * @return void
     */
    protected function handleParentMethodCall()
    {
        if (!is_callable('parent::handle')) {
            return parent::fire();
        }

        parent::handle();
    }

    /**
     * Qualifies the class name by delegating to the right parent method.
     *
     * @param string $name
     *
     * @return string
     */
    protected function qualifyClass($name)
    {
        if (!is_callable('parent::qualifyClass')) {
            return parent::parseName($name);
        }

        return parent::qualifyClass($name);
    }
}
