<?php

use App\Models\Auth\User;
use App\Models\Setting\Tax;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Tax::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns(['company_id' => $company->id]);

    $types = ['normal', 'inclusive', 'compound', 'fixed'];

    return [
        'company_id' => $company->id,
        'name' => $faker->text(15),
        'rate' => $faker->randomFloat(2, 10, 20),
        'type' => $faker->randomElement($types),
        'enabled' => $faker->boolean ? 1 : 0,
    ];
});

$factory->state(Tax::class, 'enabled', ['enabled' => 1]);

$factory->state(Tax::class, 'disabled', ['enabled' => 0]);

$factory->state(Tax::class, 'normal', ['type' => 'normal']);

$factory->state(Tax::class, 'inclusive', ['type' => 'inclusive']);

$factory->state(Tax::class, 'compound', ['type' => 'compound']);

$factory->state(Tax::class, 'fixed', ['type' => 'fixed']);
