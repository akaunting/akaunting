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

namespace League\CommonMark\Block\Renderer;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\ListBlock;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;

final class ListBlockRenderer implements BlockRendererInterface
{
    /**
     * @param ListBlock                $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return HtmlElement
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (!($block instanceof ListBlock)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        $listData = $block->getListData();

        $tag = $listData->type === ListBlock::TYPE_BULLET ? 'ul' : 'ol';

        $attrs = $block->getData('attributes', []);

        if ($listData->start !== null && $listData->start !== 1) {
            $attrs['start'] = (string) $listData->start;
        }

        return new HtmlElement(
            $tag,
            $attrs,
            $htmlRenderer->getOption('inner_separator', "\n") . $htmlRenderer->renderBlocks(
                $block->children(),
                $block->isTight()
            ) . $htmlRenderer->getOption('inner_separator', "\n")
        );
    }
}
