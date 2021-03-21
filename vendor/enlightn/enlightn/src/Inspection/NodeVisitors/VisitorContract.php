<?php

namespace Enlightn\Enlightn\Inspection\NodeVisitors;

interface VisitorContract
{
    /**
     * Get the line numbers of the node visitor.
     *
     * @return array
     */
    public function getLineNumbers();

    /**
     * Determine whether the node visitor passed.
     *
     * @return bool
     */
    public function passed();

    /**
     * Flush the node visitor (occurs once per file analyzed).
     *
     * @return void
     */
    public function flush();
}
