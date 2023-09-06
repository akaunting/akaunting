<?php

namespace Spatie\Ignition\Contracts;

interface RunnableSolution extends Solution
{
    public function getSolutionActionDescription(): string;

    public function getRunButtonText(): string;

    /** @param array<string, mixed> $parameters */
    public function run(array $parameters = []): void;

    /** @return array<string, mixed> */
    public function getRunParameters(): array;
}
