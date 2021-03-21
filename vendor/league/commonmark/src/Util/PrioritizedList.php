<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (https://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Util;

/**
 * @internal
 * @phpstan-template T
 * @phpstan-implements \IteratorAggregate<T>
 */
final class PrioritizedList implements \IteratorAggregate
{
    /**
     * @var array<int, array<mixed>>
     * @phpstan-var array<int, array<T>>
     */
    private $list = [];

    /**
     * @var iterable<mixed>|null
     * @phpstan-var iterable<T>|null
     */
    private $optimized;

    /**
     * @param mixed $item
     * @param int   $priority
     *
     * @phpstan-param T $item
     */
    public function add($item, int $priority): void
    {
        $this->list[$priority][] = $item;
        $this->optimized = null;
    }

    /**
     * @return iterable<int, mixed>
     *
     * @phpstan-return iterable<int, T>
     */
    public function getIterator(): iterable
    {
        if ($this->optimized === null) {
            \krsort($this->list);

            $sorted = [];
            foreach ($this->list as $group) {
                foreach ($group as $item) {
                    $sorted[] = $item;
                }
            }

            $this->optimized = new \ArrayIterator($sorted);
        }

        return $this->optimized;
    }
}
