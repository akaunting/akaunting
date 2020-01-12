<?php

use App\Models\Auth\User;
use App\Models\Setting\Category;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Category::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns(['company_id' => $company->id]);

    $types = ['income', 'expense', 'item', 'other'];

    return [
        'company_id' => $company->id,
        'name' => $faker->text(15),
        'type' => $faker->randomElement($types),
        'color' => $faker->hexColor,
        'enabled' => $faker->boolean ? 1 : 0,
    ];
});

$factory->state(Category::class, 'enabled', ['enabled' => 1]);

$factory->state(Category::class, 'disabled', ['enabled' => 0]);

$factory->state(Category::class, 'income', ['type' => 'income']);

$factory->state(Category::class, 'expense', ['type' => 'expense']);

$factory->state(Category::class, 'item', ['type' => 'item']);

$factory->state(Category::class, 'other', ['type' => 'other']);
