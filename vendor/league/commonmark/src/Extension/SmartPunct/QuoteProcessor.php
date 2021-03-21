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

use League\CommonMark\Delimiter\DelimiterInterface;
use League\CommonMark\Delimiter\Processor\DelimiterProcessorInterface;
use League\CommonMark\Inline\Element\AbstractStringContainer;

final class QuoteProcessor implements DelimiterProcessorInterface
{
    /** @var string */
    private $normalizedCharacter;

    /** @var string */
    private $openerCharacter;

    /** @var string */
    private $closerCharacter;

    private function __construct(string $char, string $opener, string $closer)
    {
        $this->normalizedCharacter = $char;
        $this->openerCharacter = $opener;
        $this->closerCharacter = $closer;
    }

    public function getOpeningCharacter(): string
    {
        return $this->normalizedCharacter;
    }

    public function getClosingCharacter(): string
    {
        return $this->normalizedCharacter;
    }

    public function getMinLength(): int
    {
        return 1;
    }

    public function getDelimiterUse(DelimiterInterface $opener, DelimiterInterface $closer): int
    {
        return 1;
    }

    public function process(AbstractStringContainer $opener, AbstractStringContainer $closer, int $delimiterUse)
    {
        $opener->insertAfter(new Quote($this->openerCharacter));
        $closer->insertBefore(new Quote($this->closerCharacter));
    }

    /**
     * Create a double-quote processor
     *
     * @param string $opener
     * @param string $closer
     *
     * @return QuoteProcessor
     */
    public static function createDoubleQuoteProcessor(string $opener = Quote::DOUBLE_QUOTE_OPENER, string $closer = Quote::DOUBLE_QUOTE_CLOSER): self
    {
        return new self(Quote::DOUBLE_QUOTE, $opener, $closer);
    }

    /**
     * Create a single-quote processor
     *
     * @param string $opener
     * @param string $closer
     *
     * @return QuoteProcessor
     */
    public static function createSingleQuoteProcessor(string $opener = Quote::SINGLE_QUOTE_OPENER, string $closer = Quote::SINGLE_QUOTE_CLOSER): self
    {
        return new self(Quote::SINGLE_QUOTE, $opener, $closer);
    }
}
