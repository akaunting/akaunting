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

class IndentedCode extends AbstractStringContainerBlock
{
    public function canContain(AbstractBlock $block): bool
    {
        return false;
    }

    public function isCode(): bool
    {
        return true;
    }

    public function matchesNextLine(Cursor $cursor): bool
    {
        if ($cursor->isIndented()) {
            $cursor->advanceBy(Cursor::INDENT_LEVEL, true);
        } elseif ($cursor->isBlank()) {
            $cursor->advanceToNextNonSpaceOrTab();
        } else {
            return false;
        }

        return true;
    }

    public function finalize(ContextInterface $context, int $endLineNumber)
    {
        parent::finalize($context, $endLineNumber);

        $reversed = \array_reverse($this->strings->toArray(), true);
        foreach ($reversed as $index => $line) {
            if ($line === '' || $line === "\n" || \preg_match('/^(\n *)$/', $line)) {
                unset($reversed[$index]);
            } else {
                break;
            }
        }
        $fixed = \array_reverse($reversed);
        $tmp = \implode("\n", $fixed);
        if (\substr($tmp, -1) !== "\n") {
            $tmp .= "\n";
        }

        $this->finalStringContents = $tmp;
    }

    public function handleRemainingContents(ContextInterface $context, Cursor $cursor)
    {
        /** @var self $tip */
        $tip = $context->getTip();
        $tip->addLine($cursor->getRemainder());
    }
}
