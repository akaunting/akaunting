<?php

namespace Lorisleiva\LaravelSearchString\AST;

use Lorisleiva\LaravelSearchString\Visitors\Visitor;

abstract class Symbol
{
    abstract public function accept(Visitor $visitor);
}
