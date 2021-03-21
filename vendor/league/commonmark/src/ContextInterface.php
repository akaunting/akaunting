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
use League\CommonMark\Block\Element\Document;
use League\CommonMark\Reference\ReferenceParser;

interface ContextInterface
{
    /**
     * @return Document
     */
    public function getDocument(): Document;

    /**
     * @return AbstractBlock|null
     */
    public function getTip(): ?AbstractBlock;

    /**
     * @param AbstractBlock|null $block
     *
     * @return void
     */
    public function setTip(?AbstractBlock $block);

    /**
     * @return int
     */
    public function getLineNumber(): int;

    /**
     * @return string
     */
    public function getLine(): string;

    /**
     * Finalize and close any unmatched blocks
     *
     * @return UnmatchedBlockCloser
     */
    public function getBlockCloser(): UnmatchedBlockCloser;

    /**
     * @return AbstractBlock
     */
    public function getContainer(): AbstractBlock;

    /**
     * @param AbstractBlock $container
     *
     * @return void
     */
    public function setContainer(AbstractBlock $container);

    /**
     * @param AbstractBlock $block
     *
     * @return void
     */
    public function addBlock(AbstractBlock $block);

    /**
     * @param AbstractBlock $replacement
     *
     * @return void
     */
    public function replaceContainerBlock(AbstractBlock $replacement);

    /**
     * @return bool
     */
    public function getBlocksParsed(): bool;

    /**
     * @param bool $bool
     *
     * @return $this
     */
    public function setBlocksParsed(bool $bool);

    /**
     * @return ReferenceParser
     */
    public function getReferenceParser(): ReferenceParser;
}
