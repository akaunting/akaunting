<?php

use App\Models\Auth\User;
use App\Models\Common\Contact;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Contact::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns(['company_id' => $company->id]);

    return [
        'company_id' => $company->id,
        'type' => $faker->boolean ? 'customer' : 'vendor',
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'user_id' => null,
        'tax_number' => $faker->randomNumber(9),
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'website' => 'https://akaunting.com',
        'currency_code' => setting('default.currency'),
        'reference' => $faker->text(5),
        'enabled' => $faker->boolean ? 1 : 0,
    ];
});

$factory->state(Contact::class, 'customer', function (Faker $faker) {
    return [
        'type' => 'customer',
    ];
});

$factory->state(Contact::class, 'vendor', function (Faker $faker) {
    return [
        'type' => 'vendor',
    ];
});

$factory->state(Contact::class, 'enabled', function (Faker $faker) {
    return [
        'enabled' => 1,
    ];
});

$factory->state(Contact::class, 'disabled', function (Faker $faker) {
    return [
        'enabled' => 0,
    ];
});
