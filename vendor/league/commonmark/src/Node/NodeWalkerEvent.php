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

namespace League\CommonMark\Node;

final class NodeWalkerEvent
{
    /**
     * @var Node
     */
    private $node;

    /**
     * @var bool
     */
    private $isEntering;

    /**
     * @param Node $node
     * @param bool $isEntering
     */
    public function __construct(Node $node, $isEntering = true)
    {
        $this->node = $node;
        $this->isEntering = $isEntering;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function isEntering(): bool
    {
        return $this->isEntering;
    }
}
