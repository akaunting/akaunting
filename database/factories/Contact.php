<?php

use App\Models\Auth\User;
use App\Models\Common\Contact;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Contact::class, function (Faker $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    $types = ['customer', 'vendor'];

    return [
        'company_id' => $company->id,
        'type' => $faker->randomElement($types),
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

$factory->state(Contact::class, 'enabled', ['enabled' => 1]);

$factory->state(Contact::class, 'disabled', ['enabled' => 0]);

$factory->state(Contact::class, 'customer', ['type' => 'customer']);

$factory->state(Contact::class, 'vendor', ['type' => 'vendor']);
