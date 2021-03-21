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

use League\CommonMark\Cursor;

/**
 * @method children() AbstractBlock[]
 */
class ListItem extends AbstractBlock
{
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

    public function canContain(AbstractBlock $block): bool
    {
        return true;
    }

    public function isCode(): bool
    {
        return false;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        if ($cursor->isBlank()) {
            if ($this->firstChild === null) {
                return false;
            }

            $cursor->advanceToNextNonSpaceOrTab();
        } elseif ($cursor->getIndent() >= $this->listData->markerOffset + $this->listData->padding) {
            $cursor->advanceBy($this->listData->markerOffset + $this->listData->padding, true);
        } else {
            return false;
        }

        return true;
    }

    public function shouldLastLineBeBlank(Cursor $cursor, int $currentLineNumber): bool
    {
        return $cursor->isBlank() && $this->startLine < $currentLineNumber;
    }
}
