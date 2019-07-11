<?php

namespace App\Traits;

use Akaunting\Money\Money;
use Akaunting\Money\Currency;

trait Currencies
{

    public function convert($amount, $code, $rate, $format = false)
    {
        $default = new Currency(setting('general.default_currency', 'USD'));

        if ($format) {
            $money = Money::$code($amount, true)->convert($default, (double) $rate)->format();
        } else {
            $money = Money::$code($amount)->convert($default, (double) $rate)->getAmount();
        }

        return $money;
    }

    public function divide($amount, $code, $rate, $format = false)
    {
        if ($format) {
            $money = Money::$code($amount, true)->divide((double) $rate)->format();
        } else {
            $money = Money::$code($amount)->divide((double) $rate)->getAmount();
        }

        return $money;
    }

    public function reverseConvert($amount, $code, $rate, $format = false)
    {
        $default = setting('general.default_currency', 'USD');

        $code = new Currency($code);

        if ($format) {
            $money = Money::$default($amount, true)->convert($code, (double) $rate)->format();
        } else {
            $money = Money::$default($amount)->convert($code, (double) $rate)->getAmount();
        }

        return $money;
    }

    public function dynamicConvert($default, $amount, $code, $rate, $format = false)
    {
        $code = new Currency($code);

        if ($format) {
            $money = Money::$default($amount, true)->convert($code, (double) $rate)->format();
        } else {
            $money = Money::$default($amount)->convert($code, (double) $rate)->getAmount();
        }

        return $money;
    }

    public function getConvertedAmount($format = false, $with_tax = true)
    {
        $amount = $with_tax ? $this->amount : (isset($this->amount_without_tax) ? $this->amount_without_tax : $this->amount);

        return $this->convert($amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function getReverseConvertedAmount($format = false, $with_tax = true)
    {
        $amount = $with_tax ? $this->amount : (isset($this->amount_without_tax) ? $this->amount_without_tax : $this->amount);

        return $this->reverseConvert($amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function getDynamicConvertedAmount($format = false, $with_tax = true)
    {
        $amount = $with_tax ? $this->amount : (isset($this->amount_without_tax) ? $this->amount_without_tax : $this->amount);

        return $this->dynamicConvert($this->default_currency_code, $amount, $this->currency_code, $this->currency_rate, $format);
    }
}
