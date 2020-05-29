<?php

use App\Models\Auth\User;
use App\Models\Common\Item;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Item::class, function (Faker $faker) use ($company) {
    setting()->setExtraColumns(['company_id' => $company->id]);

    return [
        'company_id' => $company->id,
        'name' => $faker->text(15),
        'description' => $faker->text(100),
        'purchase_price' => $faker->randomFloat(2, 10, 20),
        'sale_price' => $faker->randomFloat(2, 10, 20),
        'category_id' => $company->categories()->item()->get()->random(1)->pluck('id')->first(),
        'tax_id' => null,
        'enabled' => $faker->boolean ? 1 : 0,
    ];
});

$factory->state(Item::class, 'enabled', ['enabled' => 1]);

$factory->state(Item::class, 'disabled', ['enabled' => 0]);
