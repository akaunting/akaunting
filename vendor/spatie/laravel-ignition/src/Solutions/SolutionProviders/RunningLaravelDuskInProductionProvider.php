<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Exception;
use Spatie\Ignition\Contracts\BaseSolution;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Throwable;

class RunningLaravelDuskInProductionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        if (! $throwable instanceof Exception) {
            return false;
        }

        return $throwable->getMessage() === 'It is unsafe to run Dusk in production.';
    }

    public function getSolutions(Throwable $throwable): array
    {
        return [
            BaseSolution::create()
                ->setSolutionTitle('Laravel Dusk should not be run in production.')
                ->setSolutionDescription('Install the dependencies with the `--no-dev` flag.'),

            BaseSolution::create()
                ->setSolutionTitle('Laravel Dusk can be run in other environments.')
                ->setSolutionDescription('Consider setting the `APP_ENV` to something other than `production` like `local` for example.'),
        ];
    }
}
