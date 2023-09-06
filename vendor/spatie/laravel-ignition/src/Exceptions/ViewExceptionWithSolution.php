<?php

namespace Spatie\LaravelIgnition\Exceptions;

use Spatie\Ignition\Contracts\ProvidesSolution;
use Spatie\Ignition\Contracts\Solution;

class ViewExceptionWithSolution extends ViewException implements ProvidesSolution
{
    protected Solution $solution;

    public function setSolution(Solution $solution): void
    {
        $this->solution = $solution;
    }

    public function getSolution(): Solution
    {
        return $this->solution;
    }
}
