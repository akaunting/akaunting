<?php

declare(strict_types=1);

namespace Money\Formatter;

use Money\Currencies;
use Money\Money;
use Money\MoneyFormatter;

use function assert;
use function str_pad;
use function strlen;
use function substr;

/**
 * Formats a Money object as a decimal string.
 */
final class DecimalMoneyFormatter implements MoneyFormatter
{
    private Currencies $currencies;

    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;
    }

    /** @psalm-return numeric-string */
    public function format(Money $money): string
    {
        $valueBase = $money->getAmount();
        $negative  = $valueBase[0] === '-';

        if ($negative) {
            $valueBase = substr($valueBase, 1);
        }

        $subunit     = $this->currencies->subunitFor($money->getCurrency());
        $valueLength = strlen($valueBase);

        if ($valueLength > $subunit) {
            $formatted     = substr($valueBase, 0, $valueLength - $subunit);
            $decimalDigits = substr($valueBase, $valueLength - $subunit);

            if (strlen($decimalDigits) > 0) {
                $formatted .= '.' . $decimalDigits;
            }
        } else {
            $formatted = '0.' . str_pad('', $subunit - $valueLength, '0') . $valueBase;
        }

        if ($negative) {
            $formatted = '-' . $formatted;
        }

        assert($formatted !== '');

        /** @psalm-var numeric-string $formatted */
        return $formatted;
    }
}
