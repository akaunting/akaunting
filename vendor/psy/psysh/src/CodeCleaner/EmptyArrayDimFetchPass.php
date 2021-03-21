<?php

/*
 * This file is part of Psy Shell.
 *
 * (c) 2012-2020 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psy\CodeCleaner;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use Psy\Exception\FatalErrorException;

/**
 * Validate empty brackets are only used for assignment.
 */
class EmptyArrayDimFetchPass extends CodeCleanerPass
{
    const EXCEPTION_MESSAGE = 'Cannot use [] for reading';

    private $theseOnesAreFine = [];

    public function beforeTraverse(array $nodes)
    {
        $this->theseOnesAreFine = [];
    }

    /**
     * @throws FatalErrorException if the user used empty empty array dim fetch outside of assignment
     *
     * @param Node $node
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof Assign && $node->var instanceof ArrayDimFetch) {
            $this->theseOnesAreFine[] = $node->var;
        }

        if ($node instanceof ArrayDimFetch && $node->dim === null) {
            if (!\in_array($node, $this->theseOnesAreFine)) {
                throw new FatalErrorException(self::EXCEPTION_MESSAGE, $node->getLine());
            }
        }
    }
}
