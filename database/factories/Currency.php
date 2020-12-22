<?php

use App\Models\Auth\User;
use App\Models\Setting\Currency;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Currency::class, function (Faker $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    $currencies = config('money');

    Currency::pluck('code')->each(function ($db_code) use (&$currencies) {
        unset($currencies[$db_code]);
    });

    $random = $faker->randomElement($currencies);

    $filtered = array_filter($currencies, function ($value) use ($random) {
        return ($value['code'] == $random['code']);
    });

    $code = key($filtered);
    $currency = $filtered[$code];

    return [
        'company_id' => $company->id,
        'name' => $currency['name'],
        'code' => $code,
        'rate' => $faker->randomFloat($currency['precision'], 1, 10),
        'precision' => $currency['precision'],
        'symbol' => $currency['symbol'],
        'symbol_first' => $currency['symbol_first'],
        'decimal_mark' => $currency['decimal_mark'],
        'thousands_separator' => $currency['thousands_separator'],
        'enabled' => $faker->boolean ? 1 : 0,
    ];
});

$factory->state(Currency::class, 'enabled', ['enabled' => 1]);

$factory->state(Currency::class, 'disabled', ['enabled' => 0]);
