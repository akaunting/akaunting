<?php

namespace Enlightn\Enlightn\Inspection\Concerns;

use PhpParser\Node\Stmt\UseUse;

trait GathersClassAliases
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $aliases;

    public function beforeTraverse(array $nodes)
    {
        foreach ($nodes as $node) {
            if ($node instanceof UseUse
                && ($node->name->toString() === $this->class
                    || is_subclass_of($node->name->toString(), $this->class))) {
                // Register the use statement and add the alias. E.g. "use ClassName as Alias" or "use ClassName".
                // We also detect subclasses of the given class here.
                $alias = is_null($node->alias) ? $node->name->toString() : $node->alias->name;
                $this->aliases = $this->aliases->merge($alias)->unique();
            }
        }

        return null;
    }

    /**
     * Flush the node visitor (occurs once per file analyzed).
     *
     * @return void
     */
    public function flush()
    {
        parent::flush();

        $this->aliases = collect($this->class);
    }
}
