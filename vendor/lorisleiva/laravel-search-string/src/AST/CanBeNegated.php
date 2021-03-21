<?php

namespace Lorisleiva\LaravelSearchString\AST;

trait CanBeNegated
{
    /** @var bool */
    public $negated = false;

    public function negate()
    {
        $this->negated = ! $this->negated;

        return $this;
    }
}
