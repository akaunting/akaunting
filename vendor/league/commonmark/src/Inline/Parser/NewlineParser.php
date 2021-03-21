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

namespace League\CommonMark\Inline\Parser;

use League\CommonMark\Inline\Element\Newline;
use League\CommonMark\Inline\Element\Text;
use League\CommonMark\InlineParserContext;

final class NewlineParser implements InlineParserInterface
{
    public function getCharacters(): array
    {
        return ["\n"];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $inlineContext->getCursor()->advanceBy(1);

        // Check previous inline for trailing spaces
        $spaces = 0;
        $lastInline = $inlineContext->getContainer()->lastChild();
        if ($lastInline instanceof Text) {
            $trimmed = \rtrim($lastInline->getContent(), ' ');
            $spaces = \strlen($lastInline->getContent()) - \strlen($trimmed);
            if ($spaces) {
                $lastInline->setContent($trimmed);
            }
        }

        if ($spaces >= 2) {
            $inlineContext->getContainer()->appendChild(new Newline(Newline::HARDBREAK));
        } else {
            $inlineContext->getContainer()->appendChild(new Newline(Newline::SOFTBREAK));
        }

        return true;
    }
}
