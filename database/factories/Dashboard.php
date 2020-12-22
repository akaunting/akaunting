<?php

use App\Models\Auth\User;
use App\Models\Common\Dashboard;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Dashboard::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns(['company_id' => $company->id]);

    return [
        'company_id' => $company->id,
        'name' => $faker->text(15),
        'enabled' => $faker->boolean ? 1 : 0,
    ];
});

$factory->state(Dashboard::class, 'enabled', ['enabled' => 1]);

$factory->state(Dashboard::class, 'disabled', ['enabled' => 0]);

$factory->state(Dashboard::class, 'users', function (Faker $faker) use ($company) {
    return [
        'users' => $company->users()->enabled()->get()->pluck('id')->toArray(),
    ];
});

$factory->afterCreating(Dashboard::class, function ($dashboard, $faker) use ($company) {
    $dashboard->users()->attach($company->users()->enabled()->get()->pluck('id')->toArray());
});
