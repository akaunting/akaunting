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

namespace League\CommonMark\Block\Element;

use League\CommonMark\ContextInterface;
use League\CommonMark\Cursor;

/**
 * @method children() AbstractBlock[]
 */
class ListBlock extends AbstractBlock
{
    const TYPE_BULLET = 'bullet';
    const TYPE_ORDERED = 'ordered';

    /**
     * @deprecated This constant is deprecated in league/commonmark 1.4 and will be removed in 2.0; use TYPE_BULLET instead
     */
    const TYPE_UNORDERED = self::TYPE_BULLET;

    /**
     * @var bool
     */
    protected $tight = false;

    /**
     * @var ListData
     */
    protected $listData;

    public function __construct(ListData $listData)
    {
        $this->listData = $listData;
    }

    /**
     * @return ListData
     */
    public function getListData(): ListData
    {
        return $this->listData;
    }

    public function endsWithBlankLine(): bool
    {
        if ($this->lastLineBlank) {
            return true;
        }

        if ($this->hasChildren()) {
            return $this->lastChild() instanceof AbstractBlock && $this->lastChild()->endsWithBlankLine();
        }

        return false;
    }

    public function canContain(AbstractBlock $block): bool
    {
        return $block instanceof ListItem;
    }

    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        return true;
    }

    public function finalize(ContextInterface $context, int $endLineNumber)
    {
        parent::finalize($context, $endLineNumber);

        $this->tight = true; // tight by default

        foreach ($this->children() as $item) {
            if (!($item instanceof AbstractBlock)) {
                continue;
            }

            // check for non-final list item ending with blank line:
            if ($item->endsWithBlankLine() && $item !== $this->lastChild()) {
                $this->tight = false;
                break;
            }

            // Recurse into children of list item, to see if there are
            // spaces between any of them:
            foreach ($item->children() as $subItem) {
                if ($subItem instanceof AbstractBlock && $subItem->endsWithBlankLine() && ($item !== $this->lastChild() || $subItem !== $item->lastChild())) {
                    $this->tight = false;
                    break;
                }
            }
        }
    }

    public function isTight(): bool
    {
        return $this->tight;
    }

    public function setTight(bool $tight): self
    {
        $this->tight = $tight;

        return $this;
    }
}
