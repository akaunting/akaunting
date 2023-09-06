<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Spatie\LaravelIgnition\Solutions\SuggestImportSolution;
use Spatie\LaravelIgnition\Support\Composer\ComposerClassMap;
use Throwable;

class MissingImportSolutionProvider implements HasSolutionsForThrowable
{
    protected ?string $foundClass;

    protected ComposerClassMap $composerClassMap;

    public function canSolve(Throwable $throwable): bool
    {
        $pattern = '/Class \"([^\s]+)\" not found/m';

        if (! preg_match($pattern, $throwable->getMessage(), $matches)) {
            return false;
        }

        $class = $matches[1];

        $this->composerClassMap = new ComposerClassMap();

        $this->search($class);

        return ! is_null($this->foundClass);
    }

    /**
     * @param \Throwable $throwable
     *
     * @return array<int, SuggestImportSolution>
     */
    public function getSolutions(Throwable $throwable): array
    {
        if (is_null($this->foundClass)) {
            return [];
        }

        return [new SuggestImportSolution($this->foundClass)];
    }

    protected function search(string $missingClass): void
    {
        $this->foundClass = $this->composerClassMap->searchClassMap($missingClass);

        if (is_null($this->foundClass)) {
            $this->foundClass = $this->composerClassMap->searchPsrMaps($missingClass);
        }
    }
}
