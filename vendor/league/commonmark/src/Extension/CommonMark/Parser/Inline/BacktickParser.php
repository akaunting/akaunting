<?php

declare(strict_types=1);

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

namespace League\CommonMark\Extension\CommonMark\Parser\Inline;

use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

final class BacktickParser implements InlineParserInterface
{
    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex('`+');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $ticks  = $inlineContext->getFullMatch();
        $cursor = $inlineContext->getCursor();
        $cursor->advanceBy($inlineContext->getFullMatchLength());

        $currentPosition = $cursor->getPosition();
        $previousState   = $cursor->saveState();

        while ($matchingTicks = $cursor->match('/`+/m')) {
            if ($matchingTicks !== $ticks) {
                continue;
            }

            $code = $cursor->getSubstring($currentPosition, $cursor->getPosition() - $currentPosition - \strlen($ticks));

            $c = \preg_replace('/\n/m', ' ', $code) ?? '';

            if (
                $c !== '' &&
                $c[0] === ' ' &&
                \substr($c, -1, 1) === ' ' &&
                \preg_match('/[^ ]/', $c)
            ) {
                $c = \substr($c, 1, -1);
            }

            $inlineContext->getContainer()->appendChild(new Code($c));

            return true;
        }

        // If we got here, we didn't match a closing backtick sequence
        $cursor->restoreState($previousState);
        $inlineContext->getContainer()->appendChild(new Text($ticks));

        return true;
    }
}
