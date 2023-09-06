<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2023 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\CodeCleaner;

use PhpParser\Node;
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\FunctionLike;
use Psy\Exception\FatalErrorException;

class FunctionContextPass extends CodeCleanerPass
{
    /** @var int */
    private $functionDepth;

    /**
     * @param array $nodes
     *
     * @return Node[]|null Array of nodes
     */
    public function beforeTraverse(array $nodes)
    {
        $this->functionDepth = 0;
    }

    /**
     * @return int|Node|null Replacement node (or special return value)
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof FunctionLike) {
            $this->functionDepth++;

            return;
        }

        // node is inside function context
        if ($this->functionDepth !== 0) {
            return;
        }

        // It causes fatal error.
        if ($node instanceof Yield_) {
            $msg = 'The "yield" expression can only be used inside a function';
            throw new FatalErrorException($msg, 0, \E_ERROR, null, $node->getLine());
        }
    }

    /**
     * @param \PhpParser\Node $node
     *
     * @return int|Node|Node[]|null Replacement node (or special return value)
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof FunctionLike) {
            $this->functionDepth--;
        }
    }
}
