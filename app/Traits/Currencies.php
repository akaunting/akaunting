<?php

namespace App\Traits;

use Akaunting\Money\Money;
use Akaunting\Money\Currency;

trait Currencies
{
    public function convert($amount, $from, $to, $rate, $format = false)
    {
        $money = Money::$from($amount, $format);

        // No need to convert same currency
        if ($from == $to) {
            return $format ? $money->format() : $money->getAmount();
        }

        $money = $money->convert(new Currency($to), (double) $rate);

        return $format ? $money->format() : $money->getAmount();
    }

    public function divide($amount, $code, $rate, $format = false)
    {
        $money = Money::$code($amount, $format);

        $money = $money->divide((double) $rate);

        return $format ? $money->format() : $money->getAmount();
    }

    public function getAmount($with_tax = true)
    {
        return $with_tax ? $this->amount : (isset($this->amount_without_tax) ? $this->amount_without_tax : $this->amount);
    }

    public function convertToDefault($amount, $from, $rate, $format = false)
    {
        return $this->convert($amount, $from, $this->getDefaultCurrency(), $rate, $format);
    }

    public function convertFromDefault($amount, $to, $rate, $format = false)
    {
        return $this->convert($amount, $this->getDefaultCurrency(), $to, $rate, $format);
    }

    public function getAmountConvertedToDefault($format = false, $with_tax = true)
    {
        return $this->convertToDefault($this->getAmount($with_tax), $this->currency_code, $this->currency_rate, $format);
    }

    public function getAmountConvertedFromDefault($format = false, $with_tax = true)
    {
        return $this->convertFromDefault($this->getAmount($with_tax), $this->currency_code, $this->currency_rate, $format);
    }

    public function getAmountConvertedFromCustomDefault($format = false, $with_tax = true)
    {
        return $this->convert($this->getAmount($with_tax), $this->default_currency_code, $this->currency_code, $this->currency_rate, $format);
    }

    public function getAmountDivided($format = false, $with_tax = true)
    {
        return $this->divide($this->getAmount($with_tax), $this->currency_code, $this->currency_rate, $format);
    }

    protected function getDefaultCurrency()
    {
        return setting('default.currency', 'USD');
    }
}
