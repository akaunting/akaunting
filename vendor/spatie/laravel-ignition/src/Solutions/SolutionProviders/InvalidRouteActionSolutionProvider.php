<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Illuminate\Support\Str;
use Spatie\Ignition\Contracts\BaseSolution;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Spatie\LaravelIgnition\Support\Composer\ComposerClassMap;
use Spatie\LaravelIgnition\Support\StringComparator;
use Throwable;
use UnexpectedValueException;

class InvalidRouteActionSolutionProvider implements HasSolutionsForThrowable
{
    protected const REGEX = '/\[([a-zA-Z\\\\]+)\]/m';

    public function canSolve(Throwable $throwable): bool
    {
        if (! $throwable instanceof UnexpectedValueException) {
            return false;
        }

        if (! preg_match(self::REGEX, $throwable->getMessage(), $matches)) {
            return false;
        }

        return Str::startsWith($throwable->getMessage(), 'Invalid route action: ');
    }

    public function getSolutions(Throwable $throwable): array
    {
        preg_match(self::REGEX, $throwable->getMessage(), $matches);

        $invalidController = $matches[1] ?? null;

        $suggestedController = $this->findRelatedController($invalidController);

        if ($suggestedController === $invalidController) {
            return [
                BaseSolution::create("`{$invalidController}` is not invokable.")
                    ->setSolutionDescription("The controller class `{$invalidController}` is not invokable. Did you forget to add the `__invoke` method or is the controller's method missing in your routes file?"),
            ];
        }

        if ($suggestedController) {
            return [
                BaseSolution::create("`{$invalidController}` was not found.")
                    ->setSolutionDescription("Controller class `{$invalidController}` for one of your routes was not found. Did you mean `{$suggestedController}`?"),
            ];
        }

        return [
            BaseSolution::create("`{$invalidController}` was not found.")
                ->setSolutionDescription("Controller class `{$invalidController}` for one of your routes was not found. Are you sure this controller exists and is imported correctly?"),
        ];
    }

    protected function findRelatedController(string $invalidController): ?string
    {
        $composerClassMap = app(ComposerClassMap::class);

        $controllers = collect($composerClassMap->listClasses())
            ->filter(function (string $file, string $fqcn) {
                return Str::endsWith($fqcn, 'Controller');
            })
            ->mapWithKeys(function (string $file, string $fqcn) {
                return [$fqcn => class_basename($fqcn)];
            })
            ->toArray();

        $basenameMatch = StringComparator::findClosestMatch($controllers, $invalidController, 4);

        $controllers = array_flip($controllers);

        $fqcnMatch = StringComparator::findClosestMatch($controllers, $invalidController, 4);

        return $fqcnMatch ?? $basenameMatch;
    }
}
