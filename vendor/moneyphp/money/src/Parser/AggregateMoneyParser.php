<?php

namespace Money\Parser;

use Money\Currency;
use Money\Exception;
use Money\MoneyParser;

/**
 * Parses a string into a Money object using other parsers.
 *
 * @author Frederik Bosch <f.bosch@genkgo.nl>
 */
final class AggregateMoneyParser implements MoneyParser
{
    /**
     * @var MoneyParser[]
     */
    private $parsers = [];

    /**
     * @param MoneyParser[] $parsers
     */
    public function __construct(array $parsers)
    {
        if (empty($parsers)) {
            throw new \InvalidArgumentException(sprintf('Initialize an empty %s is not possible', self::class));
        }

        foreach ($parsers as $parser) {
            if (false === $parser instanceof MoneyParser) {
                throw new \InvalidArgumentException('All parsers must implement '.MoneyParser::class);
            }

            $this->parsers[] = $parser;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function parse($money, $forceCurrency = null)
    {
        if ($forceCurrency !== null && !$forceCurrency instanceof Currency) {
            @trigger_error('Passing a currency as string is deprecated since 3.1 and will be removed in 4.0. Please pass a '.Currency::class.' instance instead.', E_USER_DEPRECATED);
            $forceCurrency = new Currency($forceCurrency);
        }

        foreach ($this->parsers as $parser) {
            try {
                return $parser->parse($money, $forceCurrency);
            } catch (Exception\ParserException $e) {
            }
        }

        throw new Exception\ParserException(sprintf('Unable to parse %s', $money));
    }
}
