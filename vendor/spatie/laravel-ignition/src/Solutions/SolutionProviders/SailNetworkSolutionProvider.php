<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Spatie\Ignition\Contracts\BaseSolution;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Throwable;

class SailNetworkSolutionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        return app()->runningInConsole()
            && str_contains($throwable->getMessage(), 'php_network_getaddresses')
            && file_exists(base_path('vendor/bin/sail'))
            && file_exists(base_path('docker-compose.yml'))
            && env('LARAVEL_SAIL') === null;
    }

    public function getSolutions(Throwable $throwable): array
    {
        return [
            BaseSolution::create('Network address not found')
                ->setSolutionDescription('Did you mean to use `sail artisan`?')
                ->setDocumentationLinks([
                    'Sail: Executing Artisan Commands' => 'https://laravel.com/docs/sail#executing-artisan-commands',
                ]),
        ];
    }
}
