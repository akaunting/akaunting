<?php

use App\Models\Auth\User;
use App\Models\Banking\Transaction;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Transaction::class, function (Faker $faker) use ($company) {
	setting()->setExtraColumns(['company_id' => $company->id]);

	$types = ['income', 'expense'];
	$type = $faker->randomElement($types);

	return [
		'company_id' => $company->id,
		'type' => $type,
		'account_id' => setting('default.account'),
		'paid_at' => $faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d'),
		'amount' => $faker->randomFloat(2, 1, 1000),
		'currency_code' => setting('default.currency'),
		'currency_rate' => '1',
		'description' => $faker->text(5),
		'category_id' => $company->categories()->type($type)->get()->random(1)->pluck('id')->first(),
		'reference' => $faker->text(5),
		'payment_method' => setting('default.payment_method'),
	];
});

$factory->state(Transaction::class, 'income', function (Faker $faker) use ($company) {
    return [
		'type' => 'income',
		'category_id' => $company->categories()->income()->get()->random(1)->pluck('id')->first(),
    ];
});

$factory->state(Transaction::class, 'expense', function (Faker $faker) use ($company) {
    return [
		'type' => 'expense',
		'category_id' => $company->categories()->expense()->get()->random(1)->pluck('id')->first(),
    ];
});
