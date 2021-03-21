<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

use Enlightn\Enlightn\Inspection\Concerns\GathersClassAliases;
use PhpParser\Node;
use PhpParser\Node\Name;

class ClassInstantiationVisitor extends NodeVisitor
{
    use GathersClassAliases;

    protected $class;
    protected $parameters;

    public function __construct(string $class, $affirmativeCheck, array $parameters = [])
    {
        $this->class = $class;
        $this->affirmativeCheck = $affirmativeCheck;
        $this->parameters = $parameters;
        $this->aliases = collect($this->class);
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Expr\New_
            && $node->class instanceof Name
            && ($this->aliases->contains($node->class->toString())
                || is_subclass_of($node->class->toString(), $this->class))) {
            if (empty($this->parameters) ||
                $this->compareArguments($node->args, $this->parameters)) {
                // Here, we register the new Alias(...) or the new ClassName(...) call.
                // This also works without the use import, such as new \Fully\Qualified\ClassName().
                $this->recordLineNumbers($node, "Instance of {$this->class} class instantiated.");
            }
        }

        return null;
    }
}
