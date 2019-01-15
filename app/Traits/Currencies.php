<?php

namespace App\Traits;

use Akaunting\Money\Money;
use Akaunting\Money\Currency;
use App\Models\Setting\Currency as CurrencyModel;

trait Currencies
{
    public function convert($amount, $code, $rate, $format = false)
    {
        $default = new Currency(setting('general.default_currency', 'USD'));

        $defaultCurrency = CurrencyModel::where('code', '=', $default->getCurrency())
            ->first(['code', 'rate']);

        $defaultRate = $defaultCurrency ? $defaultCurrency->rate : 1;
        $unratedAmount = $amount / $rate;

        if ($format) {
            $money = Money::$code($unratedAmount, true)->convert($default, (double)$defaultRate)->format();
        } else {
            $money = Money::$code($unratedAmount)->convert($default, (double)$defaultRate)->getAmount();
        }

        return $money;
    }

    public function divide($amount, $code, $rate, $format = false)
    {
        if ($format) {
            $money = Money::$code($amount, true)->divide((double)$rate)->format();
        } else {
            $money = Money::$code($amount)->divide((double)$rate)->getAmount();
        }

        return $money;
    }

    public function reverseConvert($amount, $code, $rate, $format = false)
    {
        $default = setting('general.default_currency', 'USD');

        $code = new Currency($code);

        if ($format) {
            $money = Money::$default($amount, true)->convert($code, (double)$rate)->format();
        } else {
            $money = Money::$default($amount)->convert($code, (double)$rate)->getAmount();
        }

        return $money;
    }

    public function dynamicConvert($default, $amount, $code, $rate, $format = false)
    {
        $code = new Currency($code);

        if ($format) {
            $money = Money::$default($amount, true)->convert($code, (double)$rate)->format();
        } else {
            $money = Money::$default($amount)->convert($code, (double)$rate)->getAmount();
        }

        return $money;
    }

    public function getConvertedAmount($format = false, $with_tax = true)
    {
        $amount = $this->amount;
        if (! $with_tax && isset($this->amount_without_tax)) {
            $amount = $this->amount_without_tax;
        }

        return $this->convert($amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function getReverseConvertedAmount($format = false, $with_tax = true)
    {
        $amount = $this->amount;
        if (! $with_tax && isset($this->amount_without_tax)) {
            $amount = $this->amount_without_tax;
        }

        return $this->reverseConvert($amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function getDynamicConvertedAmount($format = false, $with_tax = true)
    {
        $amount = $this->amount;
        if (! $with_tax && isset($this->amount_without_tax)) {
            $amount = $this->amount_without_tax;
        }

        return $this->dynamicConvert(
            $this->default_currency_code,
            $amount,
            $this->currency_code,
            $this->currency_rate,
            $format
        );
    }
}