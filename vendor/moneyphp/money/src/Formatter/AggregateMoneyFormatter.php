<?php

declare(strict_types=1);

namespace Money\Formatter;

use Money\Exception\FormatterException;
use Money\Money;
use Money\MoneyFormatter;

/**
 * Formats a Money object using other Money formatters.
 */
final class AggregateMoneyFormatter implements MoneyFormatter
{
    /**
     * @var MoneyFormatter[] indexed by currency code
     * @psalm-var non-empty-array<non-empty-string, MoneyFormatter> indexed by currency code
     */
    private array $formatters;

    /**
     * @param MoneyFormatter[] $formatters indexed by currency code
     * @psalm-param non-empty-array<non-empty-string, MoneyFormatter> $formatters indexed by currency code
     */
    public function __construct(array $formatters)
    {
        $this->formatters = $formatters;
    }

    public function format(Money $money): string
    {
        $currencyCode = $money->getCurrency()->getCode();

        if (isset($this->formatters[$currencyCode])) {
            return $this->formatters[$currencyCode]->format($money);
        }

        if (isset($this->formatters['*'])) {
            return $this->formatters['*']->format($money);
        }

        throw new FormatterException('No formatter found for currency ' . $currencyCode);
    }
}
