<?php

namespace Akaunting\Money\Casts;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use UnexpectedValueException;

class MoneyCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): Money
    {
        if (! is_string($value)) {
            throw new UnexpectedValueException;
        }

        /** @var null|array{amount:mixed, currency:string} $value */
        $value = json_decode($value, true);

        if (! is_array($value) || ! isset($value['amount']) || ! isset($value['currency'])) {
            throw new UnexpectedValueException;
        }

        return new Money(
            $value['amount'],
            new Currency($value['currency'])
        );
    }

    public function set($model, string $key, $value, array $attributes): string
    {
        if (! $value instanceof Money) {
            throw new UnexpectedValueException;
        }

        return json_encode([
            'amount' => $value->getAmount(),
            'currency' => $value->getCurrency()->getCurrency(),
        ]);
    }
}
