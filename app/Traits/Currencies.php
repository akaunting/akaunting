<?php

namespace App\Traits;

use Akaunting\Money\Money;

trait Currencies
{
    public function convert($method, $amount, $from, $to, $rate, $format = false)
    {
        $money = Money::$to($amount, $format);

        // No need to convert same currency
        if ($from == $to) {
            return $format ? $money->format() : $money->getAmount();
        }

        try {
            $money = $money->$method((double) $rate);
        } catch (\Throwable $e) {
            report($e);

            return 0;
        }

        return $format ? $money->format() : $money->getAmount();
    }

    public function convertToDefault($amount, $from, $rate, $format = false, $default = null)
    {
        $default_currency = $default ?? $this->getDefaultCurrency();

        return $this->convert('divide', $amount, $from, $default_currency, $rate, $format);
    }

    public function convertFromDefault($amount, $to, $rate, $format = false, $default = null)
    {
        $default_currency = $default ?? $this->getDefaultCurrency();

        return $this->convert('multiply', $amount, $default_currency, $to, $rate, $format);
    }

    public function convertBetween($amount, $from_code, $from_rate, $to_code, $to_rate)
    {
        $default_amount = $amount;

        if ($from_code != setting('default.currency')) {
            $default_amount = $this->convertToDefault($amount, $from_code, $from_rate);
        }

        $converted_amount = $this->convertFromDefault($default_amount, $to_code, $to_rate, false, $from_code);

        return $converted_amount;
    }

    public function getAmountConvertedToDefault($format = false, $with_tax = true)
    {
        return $this->convertToDefault($this->getAmount($with_tax), $this->currency_code, $this->currency_rate, $format);
    }

    public function getAmountConvertedFromDefault($format = false, $with_tax = true)
    {
        return $this->convertFromDefault($this->getAmount($with_tax), $this->currency_code, $this->currency_rate, $format);
    }

    public function getAmount($with_tax = true)
    {
        return $with_tax ? $this->amount : (isset($this->amount_without_tax) ? $this->amount_without_tax : $this->amount);
    }

    public function getDefaultCurrency()
    {
        return !empty($this->default_currency_code) ? $this->default_currency_code : setting('default.currency');
    }

    public function setDefaultCurrency($code)
    {
        $this->default_currency_code = $code;
    }

    public function unsetDefaultCurrency()
    {
        unset($this->default_currency_code);
    }
}
