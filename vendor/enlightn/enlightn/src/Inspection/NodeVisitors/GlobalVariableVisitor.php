<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

use PhpParser\Node;

class GlobalVariableVisitor extends NodeVisitor
{
    protected $variableName;

    public function __construct(string $variableName, $affirmativeCheck)
    {
        $this->variableName = $variableName;
        $this->affirmativeCheck = $affirmativeCheck;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\Variable
            && $node->name === $this->variableName) {
            $this->recordLineNumbers($node, "Global variable {$this->variableName} detected.");
        }

        return null;
    }
}
