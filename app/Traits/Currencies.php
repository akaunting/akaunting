<?php

namespace App\Traits;

use Akaunting\Money\Money;
use InvalidArgumentException;
use OutOfBoundsException;
use UnexpectedValueException;

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
        } catch (InvalidArgumentException | OutOfBoundsException | UnexpectedValueException $e) {
            logger($e->getMessage());

            return 0;
        }

        return $format ? $money->format() : $money->getAmount();
    }

    public function convertToDefault($amount, $from, $rate, $format = false)
    {
        return $this->convert('divide', $amount, $from, $this->getDefaultCurrency(), $rate, $format);
    }

    public function convertFromDefault($amount, $to, $rate, $format = false)
    {
        return $this->convert('multiply', $amount, $this->getDefaultCurrency(), $to, $rate, $format);
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
        return !empty($this->default_currency_code) ? $this->default_currency_code : setting('default.currency', 'USD');
    }
}
