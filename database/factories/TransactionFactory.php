<?php

use App\Models\Auth\User;
use App\Models\Banking\Transaction;
use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

$factory->define(Transaction::class, function (Faker $faker) {
	$user = User::first();
	$company = $user->companies()->first();

	$attachment = UploadedFile::fake()->create('image.jpg');

	return [
		'company_id' => $company->id,
		'type' => 'income',
		'account_id' => setting('default.account'),
		'paid_at' => $faker->date(),
		'amount' => $faker->randomFloat(2, 2),
		'currency_code' => setting('default.currency'),
		'currency_rate' => '1',
		'description' => $faker->text(5),
		'category_id' => $company->categories()->type('income')->first()->id,
		'reference' => $faker->text(5),
		'payment_method' => setting('default.payment_method'),
		'attachment' => $attachment,
	];
});
