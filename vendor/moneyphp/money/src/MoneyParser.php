<?php

namespace Money;

/**
 * Parses a string into a Money object.
 *
 * @author Frederik Bosch <f.bosch@genkgo.nl>
 */
interface MoneyParser
{
    /**
     * Parses a string into a Money object (including currency).
     *
     * @param string               $money
     * @param Currency|string|null $forceCurrency
     *
     * @return Money
     *
     * @throws Exception\ParserException
     */
    public function parse($money, $forceCurrency = null);
}
