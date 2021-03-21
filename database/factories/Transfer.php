<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Banking\Account;
use App\Models\Banking\Transaction;
use App\Models\Banking\Transfer as Model;
use App\Models\Setting\Category;

class Transfer extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $accounts = Account::enabled()->get();

        if ($accounts->count() >= 2) {
            $random = $accounts->random(2);

            $expense_account = $random->first();
            $income_account = $random->last();
        } else {
            $expense_account = $accounts->first();

            $income_account = Account::factory()->enabled()->default_currency()->create();
        }

        $request = [
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'paid_at' => $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d'),
            'category_id' => Category::transfer(),
            'description' => $this->faker->text(20),
            'reference' => $this->faker->text(20),
        ];

        $expense_transaction = Transaction::factory()->create(array_merge($request, [
            'type' => 'expense',
            'account_id' => $expense_account->id,
        ]));

        $income_transaction = Transaction::factory()->create(array_merge($request, [
            'type' => 'income',
            'account_id' => $income_account->id,
        ]));

        return [
            'company_id' => $this->company->id,
            'expense_transaction_id' => $expense_transaction->id,
            'income_transaction_id' => $income_transaction->id,
        ];
    }
}
