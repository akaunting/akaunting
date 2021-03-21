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

use League\CommonMark\Delimiter\Delimiter;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Util\RegexHelper;

final class QuoteParser implements InlineParserInterface
{
    public const DOUBLE_QUOTES = [Quote::DOUBLE_QUOTE, Quote::DOUBLE_QUOTE_OPENER, Quote::DOUBLE_QUOTE_CLOSER];
    public const SINGLE_QUOTES = [Quote::SINGLE_QUOTE, Quote::SINGLE_QUOTE_OPENER, Quote::SINGLE_QUOTE_CLOSER];

    /**
     * @return string[]
     */
    public function getCharacters(): array
    {
        return array_merge(self::DOUBLE_QUOTES, self::SINGLE_QUOTES);
    }

    /**
     * Normalizes any quote characters found and manually adds them to the delimiter stack
     */
    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        $normalizedCharacter = $this->getNormalizedQuoteCharacter($cursor->getCharacter());

        $charBefore = $cursor->peek(-1);
        if ($charBefore === null) {
            $charBefore = "\n";
        }

        $cursor->advance();

        $charAfter = $cursor->getCharacter();
        if ($charAfter === null) {
            $charAfter = "\n";
        }

        [$leftFlanking, $rightFlanking] = $this->determineFlanking($charBefore, $charAfter);
        $canOpen = $leftFlanking && !$rightFlanking;
        $canClose = $rightFlanking;

        $node = new Quote($normalizedCharacter, ['delim' => true]);
        $inlineContext->getContainer()->appendChild($node);

        // Add entry to stack to this opener
        $inlineContext->getDelimiterStack()->push(new Delimiter($normalizedCharacter, 1, $node, $canOpen, $canClose));

        return true;
    }

    private function getNormalizedQuoteCharacter(string $character): string
    {
        if (in_array($character, self::DOUBLE_QUOTES)) {
            return Quote::DOUBLE_QUOTE;
        } elseif (in_array($character, self::SINGLE_QUOTES)) {
            return Quote::SINGLE_QUOTE;
        }

        return $character;
    }

    /**
     * @param string $charBefore
     * @param string $charAfter
     *
     * @return bool[]
     */
    private function determineFlanking(string $charBefore, string $charAfter)
    {
        $afterIsWhitespace = preg_match('/\pZ|\s/u', $charAfter);
        $afterIsPunctuation = preg_match(RegexHelper::REGEX_PUNCTUATION, $charAfter);
        $beforeIsWhitespace = preg_match('/\pZ|\s/u', $charBefore);
        $beforeIsPunctuation = preg_match(RegexHelper::REGEX_PUNCTUATION, $charBefore);

        $leftFlanking = !$afterIsWhitespace &&
            !($afterIsPunctuation &&
                !$beforeIsWhitespace &&
                !$beforeIsPunctuation);

        $rightFlanking = !$beforeIsWhitespace &&
            !($beforeIsPunctuation &&
                !$afterIsWhitespace &&
                !$afterIsPunctuation);

        return [$leftFlanking, $rightFlanking];
    }
}
