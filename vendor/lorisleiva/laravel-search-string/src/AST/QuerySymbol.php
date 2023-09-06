<?php

namespace Lorisleiva\LaravelSearchString\AST;

use Lorisleiva\LaravelSearchString\Visitors\Visitor;

class QuerySymbol extends Symbol
{
    use CanHaveRule;

    /** @var string */
    public $key;

    /** @var string */
    public $operator;

    /** @var mixed */
    public $value;

    function __construct(string $key, string $operator, $value)
    {
        $this->key = $key;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function accept(Visitor $visitor)
    {
        return $visitor->visitQuery($this);
    }
}
