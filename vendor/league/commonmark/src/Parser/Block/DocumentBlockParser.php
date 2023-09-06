<?php

declare(strict_types=1);

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Parser\Block;

use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Reference\ReferenceMapInterface;

/**
 * Parser implementation which ensures everything is added to the root-level Document
 */
final class DocumentBlockParser extends AbstractBlockContinueParser
{
    /** @psalm-readonly */
    private Document $document;

    public function __construct(ReferenceMapInterface $referenceMap)
    {
        $this->document = new Document($referenceMap);
    }

    public function getBlock(): Document
    {
        return $this->document;
    }

    public function isContainer(): bool
    {
        return true;
    }

    public function canContain(AbstractBlock $childBlock): bool
    {
        return true;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        return BlockContinue::at($cursor);
    }
}
