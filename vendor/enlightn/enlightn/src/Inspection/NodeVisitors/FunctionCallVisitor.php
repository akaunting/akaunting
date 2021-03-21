<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

use PhpParser\Node;

class FunctionCallVisitor extends NodeVisitor
{
    protected $functionName;
    protected $parameters;

    public function __construct(string $functionName, $affirmativeCheck, array $parameters = [])
    {
        $this->functionName = $functionName;
        $this->affirmativeCheck = $affirmativeCheck;
        $this->parameters = $parameters;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\FuncCall
            && $node->name instanceof Node\Name
            && $node->name->toString() === $this->functionName) {
            if (empty($this->parameters) ||
                $this->compareArguments($node->args, $this->parameters)) {
                $this->recordLineNumbers($node, "Function {$this->functionName} called.");
            }
        }

        return null;
    }
}
