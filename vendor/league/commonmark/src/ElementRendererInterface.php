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
use League\CommonMark\Inline\Element\AbstractInline;

/**
 * Renders a parsed AST to a string representation
 */
interface ElementRendererInterface
{
    /**
     * @param string $option
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function getOption(string $option, $default = null);

    /**
     * @param AbstractInline $inline
     *
     * @return string
     */
    public function renderInline(AbstractInline $inline): string;

    /**
     * @param AbstractInline[] $inlines
     *
     * @return string
     */
    public function renderInlines(iterable $inlines): string;

    /**
     * @param AbstractBlock $block
     * @param bool          $inTightList
     *
     * @throws \RuntimeException
     *
     * @return string
     */
    public function renderBlock(AbstractBlock $block, bool $inTightList = false): string;

    /**
     * @param AbstractBlock[] $blocks
     * @param bool            $inTightList
     *
     * @return string
     */
    public function renderBlocks(iterable $blocks, bool $inTightList = false): string;
}
