<?php

/*
 * This file is part of the league/commonmark package.
 *
 * (c) Colin O'Dell <colinodell@gmail.com>
 *
 * Original code based on the CommonMark JS reference parser (http://bitly.com/commonmark-js)
 *  - (c) John MacFarlane
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\CommonMark\Extension\SmartPunct;

use League\CommonMark\Inline\Element\Text;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;

final class PunctuationParser implements InlineParserInterface
{
    /**
     * @return string[]
     */
    public function getCharacters(): array
    {
        return ['-', '.'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $ch = $cursor->getCharacter();

        // Ellipses
        if ($ch === '.' && $matched = $cursor->match('/^\\.( ?\\.)\\1/')) {
            $inlineContext->getContainer()->appendChild(new Text('…'));

            return true;
        }

        // Em/En-dashes
        elseif ($ch === '-' && $matched = $cursor->match('/^(?<!-)(-{2,})/')) {
            $count = strlen($matched);
            $en_dash = '–';
            $en_count = 0;
            $em_dash = '—';
            $em_count = 0;
            if ($count % 3 === 0) { // If divisible by 3, use all em dashes
                $em_count = $count / 3;
            } elseif ($count % 2 === 0) { // If divisible by 2, use all en dashes
                $en_count = $count / 2;
            } elseif ($count % 3 === 2) { // If 2 extra dashes, use en dash for last 2; em dashes for rest
                $em_count = ($count - 2) / 3;
                $en_count = 1;
            } else { // Use en dashes for last 4 hyphens; em dashes for rest
                $em_count = ($count - 4) / 3;
                $en_count = 2;
            }
            $inlineContext->getContainer()->appendChild(new Text(
                str_repeat($em_dash, $em_count) . str_repeat($en_dash, $en_count)
            ));

            return true;
        }

        return false;
    }
}
