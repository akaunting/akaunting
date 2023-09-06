<?php

namespace Spatie\Ignition\Contracts;

use Throwable;

interface SolutionProviderRepository
{
    /**
     * @param class-string<HasSolutionsForThrowable>|HasSolutionsForThrowable $solutionProvider
     *
     * @return $this
     */
    public function registerSolutionProvider(string $solutionProvider): self;

    /**
     * @param array<class-string<HasSolutionsForThrowable>|HasSolutionsForThrowable> $solutionProviders
     *
     * @return $this
     */
    public function registerSolutionProviders(array $solutionProviders): self;

    /**
     * @param Throwable $throwable
     *
     * @return array<int, Solution>
     */
    public function getSolutionsForThrowable(Throwable $throwable): array;

    /**
     * @param class-string<Solution> $solutionClass
     *
     * @return null|Solution
     */
    public function getSolutionForClass(string $solutionClass): ?Solution;
}
