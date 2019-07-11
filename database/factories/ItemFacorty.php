<?php

use Faker\Generator;
use App\Models\Auth\User;
use App\Models\Common\Item;
use App\Models\Common\Company;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */
$factory->define(Item::class, function (Generator $faker) {
	/** @var User $user */
	$user = User::first();
	/** @var Company  $company */
	$company = $user->companies()->first();

	return [
		'name' => $faker->title,
		'sku' => $faker->languageCode,
		'company_id' => $company->id,
		'description' => $faker->text(100),
		'purchase_price' => $faker->randomFloat(2,10,20),
		'sale_price' => $faker->randomFloat(2,10,20),
		'quantity' => $faker->randomNumber(2),
		'category_id' => $company->categories()->first()->id,
		'tax_id' => $company->taxes()->first()->id,
		'enabled' => $this->faker->boolean ? 1 : 0
	];
});
