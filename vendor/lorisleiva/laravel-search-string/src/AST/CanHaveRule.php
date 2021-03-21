<?php

namespace Lorisleiva\LaravelSearchString\AST;

use Lorisleiva\LaravelSearchString\Options\Rule;

trait CanHaveRule
{
    /** @var Rule */
    public $rule;

    public function attachRule(Rule $rule)
    {
        $this->rule = $rule;

        return $this;
    }
}
