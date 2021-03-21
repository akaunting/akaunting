<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\UseUse;

class UsesClassVisitor extends NodeVisitor
{
    protected $class;

    public function __construct(string $class, $affirmativeCheck)
    {
        $this->class = $class;
        $this->affirmativeCheck = $affirmativeCheck;
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof UseUse
            && ($node->name->toString() === $this->class
                || is_subclass_of($node->name->toString(), $this->class))) {
            // Here we register a use ClassName or use ChildClass statement.
            $this->recordLineNumbers($node, "Import of class {$this->class} detected.");
        }

        return null;
    }
}
