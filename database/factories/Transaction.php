<?php

use App\Models\Auth\User;
use App\Models\Banking\Transaction;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Transaction::class, function (Faker $faker) use ($company) {
	setting()->setExtraColumns(['company_id' => $company->id]);

	return [
		'company_id' => $company->id,
		'type' => 'income',
		'account_id' => setting('default.account'),
		'paid_at' => $faker->date(),
		'amount' => $faker->randomFloat(2, 2, 1000),
		'currency_code' => setting('default.currency'),
		'currency_rate' => '1',
		'description' => $faker->text(5),
		'category_id' => $company->categories()->type('income')->pluck('id')->first(),
		'reference' => $faker->text(5),
		'payment_method' => setting('default.payment_method'),
	];
});

$factory->state(Transaction::class, 'income', []);

$factory->state(Transaction::class, 'expense', function (Faker $faker) use ($company) {
    return [
		'type' => 'expense',
		'category_id' => $company->categories()->type('expense')->pluck('id')->first(),
    ];
});
