<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

use PhpParser\Node;

class ExitStatementVisitor extends NodeVisitor
{
    public function __construct($affirmativeCheck)
    {
        $this->affirmativeCheck = $affirmativeCheck;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\Exit_) {
            $this->recordLineNumbers($node, "Exit statement detected.");
        }

        return null;
    }
}
