<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

use Enlightn\Enlightn\Inspection\InspectionLine;
use PhpParser\Node;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\NodeVisitorAbstract;
use UnexpectedValueException;

abstract class NodeVisitor extends NodeVisitorAbstract implements VisitorContract
{
    protected $lineNumbers = [];

    /**
     * Indicates if the visitor passes once the condition is met.
     *
     * @var bool
     */
    protected $affirmativeCheck = true;

    /**
     * Indicates whether the visitor found any code that meets the condition.
     *
     * @var bool
     */
    protected $found = false;

    /**
     * Get the line numbers of the node visitor.
     *
     * @return array
     */
    public function getLineNumbers()
    {
        return $this->lineNumbers;
    }

    /**
     * Determine whether the node visitor passed.
     *
     * @return bool
     */
    public function passed()
    {
        return $this->affirmativeCheck ? $this->found : ! $this->found;
    }

    /**
     * Pass or fail the visitor based on the affirmative check.
     *
     * @return $this
     */
    public function markFound()
    {
        $this->found = true;

        return $this;
    }

    /**
     * Flush the node visitor (occurs once per file analyzed).
     *
     * @return void
     */
    public function flush()
    {
        $this->lineNumbers = [];

        $this->found = false;
    }

    /**
     * Record the line numbers based on the node.
     *
     * @param $node
     * @param string|null $details
     * @return $this
     */
    protected function recordLineNumbers($node, $details = null)
    {
        $this->markFound();

        $this->lineNumbers[] = new InspectionLine($node->getStartLine(), $details);

        return $this;
    }

    /**
     * Compares node values with the expected value.
     *
     * @param  \PhpParser\Node  $node
     * @param  string|int|float|bool  $expectedValue
     * @return bool
     */
    protected function compareValue(Node $node, $expectedValue)
    {
        if (property_exists($node, 'value')) {
            // works for strings, int and float
            return $node->value === $expectedValue;
        }

        if (is_bool($expectedValue)) {
            return $node instanceof ConstFetch
                && $node->name->toString() === ($expectedValue ? 'true' : 'false');
        }

        throw new UnexpectedValueException('This value type is not yet supported');
    }

    /**
     * Compares node arguments with the expected values array.
     *
     * @param array $nodeArgs
     * @param array $expectedValues
     * @return bool
     */
    protected function compareArguments(array $nodeArgs, array $expectedValues)
    {
        if (count($nodeArgs) !== count($expectedValues)) {
            return false;
        }

        return collect($nodeArgs)->every(function ($arg, $key) use ($expectedValues) {
            return $this->compareValue($arg->value, $expectedValues[$key]);
        });
    }
}
