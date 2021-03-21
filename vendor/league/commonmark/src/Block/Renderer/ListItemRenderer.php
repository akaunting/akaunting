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
use League\CommonMark\Block\Element\ListItem;
use League\CommonMark\Block\Element\Paragraph;
use League\CommonMark\ElementRendererInterface;
use League\CommonMark\Extension\TaskList\TaskListItemMarker;
use League\CommonMark\HtmlElement;

final class ListItemRenderer implements BlockRendererInterface
{
    /**
     * @param ListItem                 $block
     * @param ElementRendererInterface $htmlRenderer
     * @param bool                     $inTightList
     *
     * @return string
     */
    public function render(AbstractBlock $block, ElementRendererInterface $htmlRenderer, bool $inTightList = false)
    {
        if (!($block instanceof ListItem)) {
            throw new \InvalidArgumentException('Incompatible block type: ' . \get_class($block));
        }

        $contents = $htmlRenderer->renderBlocks($block->children(), $inTightList);
        if (\substr($contents, 0, 1) === '<' && !$this->startsTaskListItem($block)) {
            $contents = "\n" . $contents;
        }
        if (\substr($contents, -1, 1) === '>') {
            $contents .= "\n";
        }

        $attrs = $block->getData('attributes', []);

        $li = new HtmlElement('li', $attrs, $contents);

        return $li;
    }

    private function startsTaskListItem(ListItem $block): bool
    {
        $firstChild = $block->firstChild();

        return $firstChild instanceof Paragraph && $firstChild->firstChild() instanceof TaskListItemMarker;
    }
}
