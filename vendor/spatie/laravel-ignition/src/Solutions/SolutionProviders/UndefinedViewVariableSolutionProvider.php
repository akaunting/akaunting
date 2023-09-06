<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Spatie\Ignition\Contracts\BaseSolution;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Spatie\Ignition\Contracts\Solution;
use Spatie\LaravelIgnition\Exceptions\ViewException;
use Spatie\LaravelIgnition\Solutions\MakeViewVariableOptionalSolution;
use Spatie\LaravelIgnition\Solutions\SuggestCorrectVariableNameSolution;
use Throwable;

class UndefinedViewVariableSolutionProvider implements HasSolutionsForThrowable
{
    protected string $variableName;

    protected string $viewFile;

    public function canSolve(Throwable $throwable): bool
    {
        if (! $throwable instanceof ViewException) {
            return false;
        }

        return $this->getNameAndView($throwable) !== null;
    }

    public function getSolutions(Throwable $throwable): array
    {
        $solutions = [];

        /** @phpstan-ignore-next-line  */
        extract($this->getNameAndView($throwable));

        if (! isset($variableName)) {
            return [];
        }

        if (isset($viewFile)) {
            /** @phpstan-ignore-next-line  */
            $solutions = $this->findCorrectVariableSolutions($throwable, $variableName, $viewFile);
            $solutions[] = $this->findOptionalVariableSolution($variableName, $viewFile);
        }


        return $solutions;
    }

    /**
     * @param \Spatie\LaravelIgnition\Exceptions\ViewException $throwable
     * @param string $variableName
     * @param string $viewFile
     *
     * @return array<int, \Spatie\Ignition\Contracts\Solution>
     */
    protected function findCorrectVariableSolutions(
        ViewException $throwable,
        string $variableName,
        string $viewFile
    ): array {
        return collect($throwable->getViewData())
            ->map(function ($value, $key) use ($variableName) {
                similar_text($variableName, $key, $percentage);

                return ['match' => $percentage, 'value' => $value];
            })
            ->sortByDesc('match')
            ->filter(fn ($var) => $var['match'] > 40)
            ->keys()
            ->map(fn ($suggestion) => new SuggestCorrectVariableNameSolution($variableName, $viewFile, $suggestion))
            ->map(function ($solution) {
                return $solution->isRunnable()
                    ? $solution
                    : BaseSolution::create($solution->getSolutionTitle())
                        ->setSolutionDescription($solution->getSolutionDescription());
            })
            ->toArray();
    }

    protected function findOptionalVariableSolution(string $variableName, string $viewFile): Solution
    {
        $optionalSolution = new MakeViewVariableOptionalSolution($variableName, $viewFile);

        return $optionalSolution->isRunnable()
            ? $optionalSolution
            : BaseSolution::create($optionalSolution->getSolutionTitle())
                ->setSolutionDescription($optionalSolution->getSolutionDescription());
    }

    /**
     * @param \Throwable $throwable
     *
     * @return array<string, string>|null
     */
    protected function getNameAndView(Throwable $throwable): ?array
    {
        $pattern = '/Undefined variable:? (.*?) \(View: (.*?)\)/';

        preg_match($pattern, $throwable->getMessage(), $matches);

        if (count($matches) === 3) {
            [, $variableName, $viewFile] = $matches;
            $variableName = ltrim($variableName, '$');

            return compact('variableName', 'viewFile');
        }

        return null;
    }
}
