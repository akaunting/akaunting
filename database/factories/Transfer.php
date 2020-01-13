<?php

use App\Models\Auth\User;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer;
use App\Models\Setting\Category;
use Faker\Generator as Faker;

$user = User::first();
$company = $user->companies()->first();

$factory->define(Transfer::class, function (Faker $faker) use ($company) {
    session(['company_id' => $company->id]);
    setting()->setExtraColumns(['company_id' => $company->id]);

    $accounts = Account::enabled()->get();

    if ($accounts->count() >= 2) {
        $random = $accounts->random(2);

        $expense_account = $random->first();
        $income_account = $random->last();
    } else {
        $expense_account = $accounts->first();

        $income_account = factory(Account::class)->states('enabled', 'default_currency')->create();
    }

    $request = [
        'amount' => $faker->randomFloat(2, 1, 1000),
        'paid_at' => $faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d'),
        'category_id' => Category::transfer(),
        'description' => $faker->text(20),
        'reference' => $faker->text(20),
    ];

    $expense_transaction = factory(Transaction::class)->create(array_merge($request, [
		'type' => 'expense',
        'account_id' => $expense_account->id,
    ]));

    $income_transaction = factory(Transaction::class)->create(array_merge($request, [
		'type' => 'income',
        'account_id' => $income_account->id,
    ]));

    return [
        'company_id' => $company->id,
        'expense_transaction_id' => $expense_transaction->id,
        'income_transaction_id' => $income_transaction->id,
    ];
});
