<?php

namespace Lorisleiva\LaravelSearchString\AST;

use Lorisleiva\LaravelSearchString\Visitors\Visitor;

class RelationshipSymbol extends Symbol
{
    use CanHaveRule;

    /** @var string */
    public $key;

    /** @var Symbol */
    public $expression;

    /** @var string */
    public $expectedOperator;

    /** @var int */
    public $expectedCount;

    function __construct(string $key, Symbol $expression, string $expectedOperator = '>', int $expectedCount = 0)
    {
        $this->key = $key;
        $this->expression = $expression;
        $this->expectedOperator = $expectedOperator;
        $this->expectedCount = $expectedCount;
    }

    public function accept(Visitor $visitor)
    {
        return $visitor->visitRelationship($this);
    }

    public function getNormalizedExpectedOperation($normalizeComparisons = false)
    {
        switch (true) {
            case $this->isCheckingExistance():
                return ['>=', 1];
            case $this->isCheckingInexistance():
                return ['<', 1];
            case $normalizeComparisons && $this->expectedOperator === '>':
                return ['>=', $this->expectedCount + 1];
            case $normalizeComparisons && $this->expectedOperator === '<=':
                return ['<', $this->expectedCount + 1];
            default:
                return [$this->expectedOperator, $this->expectedCount];
        }
    }

    public function getExpectedOperation(): string
    {
        return sprintf('%s %s', $this->expectedOperator, $this->expectedCount);
    }

    public function isCheckingExistance(): bool
    {
        return in_array($this->getExpectedOperation(), ['> 0', '!= 0', '>= 1']);
    }

    public function isCheckingInexistance(): bool
    {
        return in_array($this->getExpectedOperation(), ['<= 0', '= 0', '< 1']);
    }

    public function match(Symbol $that): bool
    {
        if (! $that instanceof RelationshipSymbol) {
            return false;
        }

        $thisOperation = $this->getNormalizedExpectedOperation(true);
        $thatOperation = $that->getNormalizedExpectedOperation(true);

        return $this->rule && $that->rule
            && $this->rule->column === $that->rule->column
            && $thisOperation === $thatOperation;
    }
}
