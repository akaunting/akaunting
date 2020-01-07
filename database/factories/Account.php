<?php

use App\Models\Auth\User;
use App\Models\Banking\Account;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Account::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns(['company_id' => $company->id]);

    return [
        'company_id' => $company->id,
        'name' => $faker->text(5),
        'number' => (string) $faker->randomNumber(2),
        'currency_code' => $company->currencies()->enabled()->get()->random(1)->pluck('code')->first(),
        'opening_balance' => '0',
        'bank_name' => $faker->text(5),
        'bank_phone' => $faker->phoneNumber,
        'bank_address' => $faker->address,
        'enabled' => $faker->boolean ? 1 : 0,
    ];
});

$factory->state(Account::class, 'enabled', function (Faker $faker) {
    return [
        'enabled' => 1,
    ];
});

$factory->state(Account::class, 'disabled', function (Faker $faker) {
    return [
        'enabled' => 0,
    ];
});

$factory->state(Account::class, 'default_currency', function (Faker $faker) {
    return [
        'currency_code' => setting('default.currency'),
    ];
});
