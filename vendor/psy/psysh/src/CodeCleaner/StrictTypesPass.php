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
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Declare_;
use PhpParser\Node\Stmt\DeclareDeclare;
use Psy\Exception\FatalErrorException;

/**
 * Provide implicit strict types declarations for for subsequent execution.
 *
 * The strict types pass remembers the last strict types declaration:
 *
 *     declare(strict_types=1);
 *
 * ... which it then applies implicitly to all future evaluated code, until it
 * is replaced by a new declaration.
 */
class StrictTypesPass extends CodeCleanerPass
{
    const EXCEPTION_MESSAGE = 'strict_types declaration must have 0 or 1 as its value';

    private $strictTypes = false;

    /**
     * @param bool $strictTypes enforce strict types by default
     */
    public function __construct(bool $strictTypes = false)
    {
        $this->strictTypes = $strictTypes;
    }

    /**
     * If this is a standalone strict types declaration, remember it for later.
     *
     * Otherwise, apply remembered strict types declaration to to the code until
     * a new declaration is encountered.
     *
     * @throws FatalErrorException if an invalid `strict_types` declaration is found
     *
     * @param array $nodes
     *
     * @return Node[]|null Array of nodes
     */
    public function beforeTraverse(array $nodes)
    {
        $prependStrictTypes = $this->strictTypes;

        foreach ($nodes as $node) {
            if ($node instanceof Declare_) {
                foreach ($node->declares as $declare) {
                    // For PHP Parser 4.x
                    $declareKey = $declare->key instanceof Identifier ? $declare->key->toString() : $declare->key;
                    if ($declareKey === 'strict_types') {
                        $value = $declare->value;
                        if (!$value instanceof LNumber || ($value->value !== 0 && $value->value !== 1)) {
                            throw new FatalErrorException(self::EXCEPTION_MESSAGE, 0, \E_ERROR, null, $node->getLine());
                        }

                        $this->strictTypes = $value->value === 1;
                    }
                }
            }
        }

        if ($prependStrictTypes) {
            $first = \reset($nodes);
            if (!$first instanceof Declare_) {
                $declare = new Declare_([new DeclareDeclare('strict_types', new LNumber(1))]);
                \array_unshift($nodes, $declare);
            }
        }

        return $nodes;
    }
}
