<?php

use App\Models\Auth\User;
use App\Models\Common\Item;
use Faker\Generator as Faker;

$factory->define(Item::class, function (Faker $faker) {
	$user = User::first();
	$company = $user->companies()->first();

	return [
		'company_id' => $company->id,
		'name' => $faker->text(15),
		'description' => $faker->text(100),
		'purchase_price' => $faker->randomFloat(2, 10, 20),
		'sale_price' => $faker->randomFloat(2, 10, 20),
		'category_id' => $company->categories()->type('item')->pluck('id')->first(),
		'tax_id' => '',
		'enabled' => $faker->boolean ? 1 : 0
	];
});
