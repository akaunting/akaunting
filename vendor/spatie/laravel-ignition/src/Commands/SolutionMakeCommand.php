<?php

namespace Spatie\LaravelIgnition\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class SolutionMakeCommand extends GeneratorCommand
{
    protected $name = 'ignition:make-solution';

    protected $description = 'Create a new custom Ignition solution class';

    protected $type = 'Solution';

    protected function getStub(): string
    {
        return $this->option('runnable')
            ? __DIR__.'/stubs/runnable-solution.stub'
            : __DIR__.'/stubs/solution.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return "{$rootNamespace}\\Solutions";
    }

    /** @return array<int, mixed> */
    protected function getOptions(): array
    {
        return [
            ['runnable', null, InputOption::VALUE_NONE, 'Create runnable solution'],
        ];
    }
}
