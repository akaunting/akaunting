<?php

use App\Models\Auth\User;
use App\Models\Banking\Account;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Account::class, function (Faker $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    return [
        'company_id' => $company->id,
        'name' => $faker->text(15),
        'number' => (string) $faker->iban(),
        'currency_code' => $company->currencies()->enabled()->get()->random(1)->pluck('code')->first(),
        'opening_balance' => '0',
        'bank_name' => $faker->text(15),
        'bank_phone' => $faker->phoneNumber,
        'bank_address' => $faker->address,
        'enabled' => $faker->boolean ? 1 : 0,
    ];
});

$factory->state(Account::class, 'enabled', ['enabled' => 1]);

$factory->state(Account::class, 'disabled', ['enabled' => 0]);

$factory->state(Account::class, 'default_currency', function (Faker $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    return [
        'currency_code' => setting('default.currency'),
    ];
});
