<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

use Enlightn\Enlightn\Inspection\Concerns\GathersClassAliases;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;

class StaticMethodCallVisitor extends NodeVisitor
{
    use GathersClassAliases;

    protected $class;
    protected $methodName;
    protected $parameters;

    public function __construct(string $class, string $methodName, $affirmativeCheck, array $parameters = [])
    {
        $this->class = $class;
        $this->methodName = $methodName;
        $this->affirmativeCheck = $affirmativeCheck;
        $this->parameters = $parameters;
        $this->aliases = collect($this->class);
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof StaticCall
            && $node->class instanceof Name
            && ($this->aliases->contains($node->class->toString())
                || is_subclass_of($node->class->toString(), $this->class))
            && $node->name instanceof Identifier
            && $node->name->name === $this->methodName) {
            if (empty($this->parameters) ||
                $this->compareArguments($node->args, $this->parameters)) {
                $this->recordLineNumbers($node, "Static method {$this->methodName} detected on class {$this->class}.");

                return null;
            }
        }

        return null;
    }
}
