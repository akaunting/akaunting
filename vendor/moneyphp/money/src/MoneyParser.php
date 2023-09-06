<?php

declare(strict_types=1);

namespace Money;

/**
 * Parses a string into a Money object.
 */
interface MoneyParser
{
    /**
     * Parses a string into a Money object (including currency).
     *
     * @throws Exception\ParserException
     */
    public function parse(string $money, Currency|null $fallbackCurrency = null): Money;
}
