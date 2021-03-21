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
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Stmt\Goto_;
use PhpParser\Node\Stmt\Label;
use Psy\Exception\FatalErrorException;

/**
 * CodeCleanerPass for label context.
 *
 * This class partially emulates the PHP label specification.
 * PsySH can not declare labels by sequentially executing lines with eval,
 * but since it is not a syntax error, no error is raised.
 * This class warns before invalid goto causes a fatal error.
 * Since this is a simple checker, it does not block real fatal error
 * with complex syntax.  (ex. it does not parse inside function.)
 *
 * @see http://php.net/goto
 */
class LabelContextPass extends CodeCleanerPass
{
    /** @var int */
    private $functionDepth;

    /** @var array */
    private $labelDeclarations;
    /** @var array */
    private $labelGotos;

    /**
     * @param array $nodes
     */
    public function beforeTraverse(array $nodes)
    {
        $this->functionDepth = 0;
        $this->labelDeclarations = [];
        $this->labelGotos = [];
    }

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

        if ($node instanceof Goto_) {
            $this->labelGotos[\strtolower($node->name)] = $node->getLine();
        } elseif ($node instanceof Label) {
            $this->labelDeclarations[\strtolower($node->name)] = $node->getLine();
        }
    }

    /**
     * @param \PhpParser\Node $node
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof FunctionLike) {
            $this->functionDepth--;
        }
    }

    public function afterTraverse(array $nodes)
    {
        foreach ($this->labelGotos as $name => $line) {
            if (!isset($this->labelDeclarations[$name])) {
                $msg = "'goto' to undefined label '{$name}'";
                throw new FatalErrorException($msg, 0, \E_ERROR, null, $line);
            }
        }
    }
}
