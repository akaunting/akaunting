<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

use PhpParser\Node;

class EvalExpressionVisitor extends NodeVisitor
{
    public function __construct($affirmativeCheck)
    {
        $this->affirmativeCheck = $affirmativeCheck;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\Eval_) {
            $this->recordLineNumbers($node, "Eval statement detected.");
        }

        return null;
    }
}
