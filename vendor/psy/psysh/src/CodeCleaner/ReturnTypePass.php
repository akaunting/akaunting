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
use PhpParser\Node\Expr\Closure;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Return_;
use Psy\Exception\FatalErrorException;

/**
 * Add runtime validation for return types.
 */
class ReturnTypePass extends CodeCleanerPass
{
    const MESSAGE = 'A function with return type must return a value';
    const NULLABLE_MESSAGE = 'A function with return type must return a value (did you mean "return null;" instead of "return;"?)';
    const VOID_MESSAGE = 'A void function must not return a value';
    const VOID_NULL_MESSAGE = 'A void function must not return a value (did you mean "return;" instead of "return null;"?)';
    const NULLABLE_VOID_MESSAGE = 'Void type cannot be nullable';

    private $atLeastPhp71;
    private $returnTypeStack = [];

    public function __construct()
    {
        $this->atLeastPhp71 = \version_compare(\PHP_VERSION, '7.1', '>=');
    }

    /**
     * {@inheritdoc}
     */
    public function enterNode(Node $node)
    {
        if (!$this->atLeastPhp71) {
            return; // @codeCoverageIgnore
        }

        if ($this->isFunctionNode($node)) {
            $this->returnTypeStack[] = $node->returnType;

            return;
        }

        if (!empty($this->returnTypeStack) && $node instanceof Return_) {
            $expectedType = \end($this->returnTypeStack);
            if ($expectedType === null) {
                return;
            }

            $msg = null;

            if ($this->typeName($expectedType) === 'void') {
                // Void functions
                if ($expectedType instanceof NullableType) {
                    $msg = self::NULLABLE_VOID_MESSAGE;
                } elseif ($node->expr instanceof ConstFetch && \strtolower($node->expr->name) === 'null') {
                    $msg = self::VOID_NULL_MESSAGE;
                } elseif ($node->expr !== null) {
                    $msg = self::VOID_MESSAGE;
                }
            } else {
                // Everything else
                if ($node->expr === null) {
                    $msg = $expectedType instanceof NullableType ? self::NULLABLE_MESSAGE : self::MESSAGE;
                }
            }

            if ($msg !== null) {
                throw new FatalErrorException($msg, 0, \E_ERROR, null, $node->getLine());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function leaveNode(Node $node)
    {
        if (!$this->atLeastPhp71) {
            return; // @codeCoverageIgnore
        }

        if (!empty($this->returnTypeStack) && $this->isFunctionNode($node)) {
            \array_pop($this->returnTypeStack);
        }
    }

    private function isFunctionNode(Node $node)
    {
        return $node instanceof Function_ || $node instanceof Closure;
    }

    private function typeName(Node $node)
    {
        if ($node instanceof NullableType) {
            return \strtolower($node->type->name);
        }

        if ($node instanceof Identifier) {
            return \strtolower($node->name);
        }

        throw new \InvalidArgumentException('Unable to find type name');
    }
}
