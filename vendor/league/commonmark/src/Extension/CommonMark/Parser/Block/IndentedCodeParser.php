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

namespace League\CommonMark\Extension\CommonMark\Parser\Block;

use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Parser\Block\AbstractBlockContinueParser;
use League\CommonMark\Parser\Block\BlockContinue;
use League\CommonMark\Parser\Block\BlockContinueParserInterface;
use League\CommonMark\Parser\Cursor;
use League\CommonMark\Util\ArrayCollection;

final class IndentedCodeParser extends AbstractBlockContinueParser
{
    /** @psalm-readonly */
    private IndentedCode $block;

    /** @var ArrayCollection<string> */
    private ArrayCollection $strings;

    public function __construct()
    {
        $this->block   = new IndentedCode();
        $this->strings = new ArrayCollection();
    }

    public function getBlock(): IndentedCode
    {
        return $this->block;
    }

    public function tryContinue(Cursor $cursor, BlockContinueParserInterface $activeBlockParser): ?BlockContinue
    {
        if ($cursor->isIndented()) {
            $cursor->advanceBy(Cursor::INDENT_LEVEL, true);

            return BlockContinue::at($cursor);
        }

        if ($cursor->isBlank()) {
            $cursor->advanceToNextNonSpaceOrTab();

            return BlockContinue::at($cursor);
        }

        return BlockContinue::none();
    }

    public function addLine(string $line): void
    {
        $this->strings[] = $line;
    }

    public function closeBlock(): void
    {
        $reversed = \array_reverse($this->strings->toArray(), true);
        foreach ($reversed as $index => $line) {
            if ($line !== '' && $line !== "\n" && ! \preg_match('/^(\n *)$/', $line)) {
                break;
            }

            unset($reversed[$index]);
        }

        $fixed = \array_reverse($reversed);
        $tmp   = \implode("\n", $fixed);
        if (\substr($tmp, -1) !== "\n") {
            $tmp .= "\n";
        }

        $this->block->setLiteral($tmp);
    }
}
