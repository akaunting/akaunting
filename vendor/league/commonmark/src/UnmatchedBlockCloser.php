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

/**
 * @internal
 */
class UnmatchedBlockCloser
{
    /**
     * @var ContextInterface
     */
    private $context;

    /**
     * @var AbstractBlock
     */
    private $oldTip;

    /**
     * @var AbstractBlock
     */
    private $lastMatchedContainer;

    /**
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;

        $this->resetTip();
    }

    /**
     * @param AbstractBlock $block
     *
     * @return void
     */
    public function setLastMatchedContainer(AbstractBlock $block)
    {
        $this->lastMatchedContainer = $block;
    }

    /**
     * @return void
     */
    public function closeUnmatchedBlocks()
    {
        $endLine = $this->context->getLineNumber() - 1;

        while ($this->oldTip !== $this->lastMatchedContainer) {
            /** @var AbstractBlock $oldTip */
            $oldTip = $this->oldTip->parent();
            $this->oldTip->finalize($this->context, $endLine);
            $this->oldTip = $oldTip;
        }
    }

    /**
     * @return void
     */
    public function resetTip()
    {
        if ($this->context->getTip() === null) {
            throw new \RuntimeException('No tip to reset to');
        }

        $this->oldTip = $this->context->getTip();
    }

    public function areAllClosed(): bool
    {
        return $this->context->getTip() === $this->lastMatchedContainer;
    }
}
