<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

use PhpParser\Node;

class GlobalStatementVisitor extends NodeVisitor
{
    public function __construct($affirmativeCheck)
    {
        $this->affirmativeCheck = $affirmativeCheck;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Global_) {
            $this->recordLineNumbers($node, "Global statement detected.");
        }

        return null;
    }
}
