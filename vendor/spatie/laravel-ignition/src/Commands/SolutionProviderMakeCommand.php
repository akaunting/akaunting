<?php

namespace Spatie\LaravelIgnition\Commands;

use Illuminate\Console\GeneratorCommand;

class SolutionProviderMakeCommand extends GeneratorCommand
{
    protected $name = 'ignition:make-solution-provider';

    protected $description = 'Create a new custom Ignition solution provider class';

    protected $type = 'Solution Provider';

    protected function getStub(): string
    {
        return __DIR__.'/stubs/solution-provider.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\\SolutionProviders";
    }
}
