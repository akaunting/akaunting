<?php

namespace Lorisleiva\LaravelSearchString\AST;

use Lorisleiva\LaravelSearchString\Visitors\Visitor;

class NotSymbol extends Symbol
{
    /** @var Symbol */
    public $expression;

    function __construct(Symbol $expression)
    {
        $this->expression = $expression;
    }

    public function accept(Visitor $visitor)
    {
        return $visitor->visitNot($this);
    }
}
