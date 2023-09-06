<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionProviders;

use Livewire\Exceptions\MethodNotFoundException;
use Spatie\Ignition\Contracts\HasSolutionsForThrowable;
use Spatie\LaravelIgnition\Solutions\SuggestLivewireMethodNameSolution;
use Spatie\LaravelIgnition\Support\LivewireComponentParser;
use Throwable;

class UndefinedLivewireMethodSolutionProvider implements HasSolutionsForThrowable
{
    public function canSolve(Throwable $throwable): bool
    {
        return $throwable instanceof MethodNotFoundException;
    }

    public function getSolutions(Throwable $throwable): array
    {
        ['methodName' => $methodName, 'component' => $component] = $this->getMethodAndComponent($throwable);

        if ($methodName === null || $component === null) {
            return [];
        }

        $parsed = LivewireComponentParser::create($component);

        return $parsed->getMethodNamesLike($methodName)
            ->map(function (string $suggested) use ($parsed, $methodName) {
                return new SuggestLivewireMethodNameSolution(
                    $methodName,
                    $parsed->getComponentClass(),
                    $suggested
                );
            })
            ->toArray();
    }

    /** @return array<string, string|null> */
    protected function getMethodAndComponent(Throwable $throwable): array
    {
        preg_match_all('/\[([\d\w\-_]*)\]/m', $throwable->getMessage(), $matches, PREG_SET_ORDER);

        return [
            'methodName' => $matches[0][1] ?? null,
            'component' => $matches[1][1] ?? null,
        ];
    }
}
