<?php

namespace Spatie\LaravelIgnition\Solutions\SolutionTransformers;

use Spatie\Ignition\Contracts\RunnableSolution;
use Spatie\Ignition\Solutions\SolutionTransformer;
use Spatie\LaravelIgnition\Http\Controllers\ExecuteSolutionController;
use Throwable;

class LaravelSolutionTransformer extends SolutionTransformer
{
    /** @return array<string|mixed> */
    public function toArray(): array
    {
        $baseProperties = parent::toArray();

        if (! $this->isRunnable()) {
            return $baseProperties;
        }

        /** @var RunnableSolution $solution Type shenanigans */
        $solution = $this->solution;

        $runnableProperties = [
            'is_runnable' => true,
            'action_description' => $solution->getSolutionActionDescription(),
            'run_button_text' => $solution->getRunButtonText(),
            'execute_endpoint' => $this->executeEndpoint(),
            'run_parameters' => $solution->getRunParameters(),
        ];

        return array_merge($baseProperties, $runnableProperties);
    }

    protected function isRunnable(): bool
    {
        if (! $this->solution instanceof RunnableSolution) {
            return false;
        }

        if (! $this->executeEndpoint()) {
            return false;
        }

        return true;
    }

    protected function executeEndpoint(): ?string
    {
        try {
            // The action class needs to be prefixed with a `\` to Laravel from trying
            // to add its own global namespace from RouteServiceProvider::$namespace.

            return action('\\'.ExecuteSolutionController::class);
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }
    }
}
