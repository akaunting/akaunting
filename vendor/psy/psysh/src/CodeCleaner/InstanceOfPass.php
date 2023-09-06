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
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Scalar;
use PhpParser\Node\Scalar\Encapsed;
use Psy\Exception\FatalErrorException;

/**
 * Validate that the instanceof statement does not receive a scalar value or a non-class constant.
 *
 * @author Martin Hasoň <martin.hason@gmail.com>
 */
class InstanceOfPass extends CodeCleanerPass
{
    const EXCEPTION_MSG = 'instanceof expects an object instance, constant given';

    private $atLeastPhp73;

    public function __construct()
    {
        $this->atLeastPhp73 = \version_compare(\PHP_VERSION, '7.3', '>=');
    }

    /**
     * Validate that the instanceof statement does not receive a scalar value or a non-class constant.
     *
     * @throws FatalErrorException if a scalar or a non-class constant is given
     *
     * @param Node $node
     *
     * @return int|Node|null Replacement node (or special return value)
     */
    public function enterNode(Node $node)
    {
        // Basically everything is allowed in PHP 7.3 :)
        if ($this->atLeastPhp73) {
            return;
        }

        if (!$node instanceof Instanceof_) {
            return;
        }

        if (($node->expr instanceof Scalar && !$node->expr instanceof Encapsed) ||
            $node->expr instanceof BinaryOp ||
            $node->expr instanceof Array_ ||
            $node->expr instanceof ConstFetch ||
            $node->expr instanceof ClassConstFetch
        ) {
            throw new FatalErrorException(self::EXCEPTION_MSG, 0, \E_ERROR, null, $node->getLine());
        }
    }
}
