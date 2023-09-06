<?php

namespace Lorisleiva\LaravelSearchString\AST;

use Lorisleiva\LaravelSearchString\Visitors\Visitor;

class EmptySymbol extends Symbol
{
    public function accept(Visitor $visitor)
    {
        return $visitor->visitEmpty($this);
    }
}
