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

namespace League\CommonMark;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\AbstractStringContainerBlock;
use League\CommonMark\Delimiter\DelimiterStack;
use League\CommonMark\Reference\ReferenceMapInterface;

class InlineParserContext
{
    /** @var AbstractStringContainerBlock */
    private $container;
    /** @var ReferenceMapInterface */
    private $referenceMap;
    /** @var Cursor */
    private $cursor;
    /** @var DelimiterStack */
    private $delimiterStack;

    public function __construct(AbstractStringContainerBlock $container, ReferenceMapInterface $referenceMap)
    {
        $this->referenceMap = $referenceMap;
        $this->container = $container;
        $this->cursor = new Cursor(\trim($container->getStringContent()));
        $this->delimiterStack = new DelimiterStack();
    }

    public function getContainer(): AbstractBlock
    {
        return $this->container;
    }

    public function getReferenceMap(): ReferenceMapInterface
    {
        return $this->referenceMap;
    }

    public function getCursor(): Cursor
    {
        return $this->cursor;
    }

    public function getDelimiterStack(): DelimiterStack
    {
        return $this->delimiterStack;
    }
}
