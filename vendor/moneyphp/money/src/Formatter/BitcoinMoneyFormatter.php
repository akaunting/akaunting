<?php

declare(strict_types=1);

namespace Money\Formatter;

use Money\Currencies;
use Money\Currencies\BitcoinCurrencies;
use Money\Exception\FormatterException;
use Money\Money;
use Money\MoneyFormatter;
use Money\Number;

use function str_pad;
use function strlen;
use function strpos;
use function substr;

/**
 * Formats Money to Bitcoin currency.
 */
final class BitcoinMoneyFormatter implements MoneyFormatter
{
    private int $fractionDigits;

    private Currencies $currencies;

    public function __construct(int $fractionDigits, Currencies $currencies)
    {
        $this->fractionDigits = $fractionDigits;
        $this->currencies     = $currencies;
    }

    public function format(Money $money): string
    {
        if ($money->getCurrency()->getCode() !== BitcoinCurrencies::CODE) {
            throw new FormatterException('Bitcoin Formatter can only format Bitcoin currency');
        }

        $valueBase = $money->getAmount();
        $negative  = false;

        if ($valueBase[0] === '-') {
            $negative  = true;
            $valueBase = substr($valueBase, 1);
        }

        $subunit     = $this->currencies->subunitFor($money->getCurrency());
        $valueBase   = Number::roundMoneyValue($valueBase, $this->fractionDigits, $subunit);
        $valueLength = strlen($valueBase);

        if ($valueLength > $subunit) {
            $formatted = substr($valueBase, 0, $valueLength - $subunit);

            if ($subunit) {
                $formatted .= '.';
                $formatted .= substr($valueBase, $valueLength - $subunit);
            }
        } else {
            $formatted = '0.' . str_pad('', $subunit - $valueLength, '0') . $valueBase;
        }

        if ($this->fractionDigits === 0) {
            $formatted = substr($formatted, 0, (int) strpos($formatted, '.'));
        } elseif ($this->fractionDigits > $subunit) {
            $formatted .= str_pad('', $this->fractionDigits - $subunit, '0');
        } elseif ($this->fractionDigits < $subunit) {
            $lastDigit = (int) strpos($formatted, '.') + $this->fractionDigits + 1;
            $formatted = substr($formatted, 0, $lastDigit);
        }

        $formatted = BitcoinCurrencies::SYMBOL . $formatted;

        if ($negative) {
            $formatted = '-' . $formatted;
        }

        return $formatted;
    }
}
