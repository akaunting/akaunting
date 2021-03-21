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

use League\CommonMark\Inline\Element\Code;
use League\CommonMark\Inline\Element\Text;
use League\CommonMark\InlineParserContext;

final class BacktickParser implements InlineParserInterface
{
    public function getCharacters(): array
    {
        return ['`'];
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();

        $ticks = $cursor->match('/^`+/');

        $currentPosition = $cursor->getPosition();
        $previousState = $cursor->saveState();

        while ($matchingTicks = $cursor->match('/`+/m')) {
            if ($matchingTicks === $ticks) {
                $code = $cursor->getSubstring($currentPosition, $cursor->getPosition() - $currentPosition - \strlen($ticks));

                $c = \preg_replace('/\n/m', ' ', $code);

                if (
                    !empty($c) &&
                    $c[0] === ' ' &&
                    \substr($c, -1, 1) === ' ' &&
                    \preg_match('/[^ ]/', $c)
                ) {
                    $c = \substr($c, 1, -1);
                }

                $inlineContext->getContainer()->appendChild(new Code($c));

                return true;
            }
        }

        // If we got here, we didn't match a closing backtick sequence
        $cursor->restoreState($previousState);
        $inlineContext->getContainer()->appendChild(new Text($ticks));

        return true;
    }
}
