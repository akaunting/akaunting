<?php

namespace Akaunting\Money\Casts;

use Akaunting\Money\Currency;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use UnexpectedValueException;

class CurrencyCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Currency
    {
        if (! is_string($value)) {
            throw new UnexpectedValueException;
        }

        return new Currency($value);
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if (! $value instanceof Currency) {
            throw new UnexpectedValueException;
        }

        return $value->getCurrency();
    }
}
