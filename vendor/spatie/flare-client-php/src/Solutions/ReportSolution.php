<?php

namespace Spatie\FlareClient\Solutions;

use Spatie\Ignition\Contracts\RunnableSolution;
use Spatie\Ignition\Contracts\Solution as SolutionContract;

class ReportSolution
{
    protected SolutionContract $solution;

    public function __construct(SolutionContract $solution)
    {
        $this->solution = $solution;
    }

    public static function fromSolution(SolutionContract $solution): self
    {
        return new self($solution);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $isRunnable = ($this->solution instanceof RunnableSolution);

        return [
            'class' => get_class($this->solution),
            'title' => $this->solution->getSolutionTitle(),
            'description' => $this->solution->getSolutionDescription(),
            'links' => $this->solution->getDocumentationLinks(),
            /** @phpstan-ignore-next-line  */
            'action_description' => $isRunnable ? $this->solution->getSolutionActionDescription() : null,
            'is_runnable' => $isRunnable,
            'ai_generated' => $this->solution->aiGenerated ?? false,
        ];
    }
}
