<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Banking\Transaction as Model;
use App\Traits\Transactions;
use App\Utilities\Date;

class Transaction extends Factory
{
    use Transactions;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * The type of the model.
     *
     * @var string
     */
    protected $type = 'income';

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $types = array_merge($this->getIncomeTypes(), $this->getExpenseTypes());
        $this->type = $this->faker->randomElement($types);

        $category_type = in_array($this->type, $this->getIncomeTypes()) ? 'income' : 'expense';

        return [
            'company_id' => $this->company->id,
            'type' => $this->type,
            'number' => $this->getNextTransactionNumber(),
            'account_id' => setting('default.account'),
            'paid_at' => $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s'),
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'currency_code' => setting('default.currency'),
            'currency_rate' => '1.0',
            'description' => $this->faker->text(5),
            'category_id' => $this->company->categories()->$category_type()->get()->random(1)->pluck('id')->first(),
            'reference' => $this->faker->text(5),
            'payment_method' => setting('default.payment_method'),
            'created_from' => 'core::factory',
        ];
    }

    /**
     * Indicate that the model type is income.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function income()
    {
        return $this->state([
            'type' => 'income',
            'category_id' => $this->company->categories()->income()->get()->random(1)->pluck('id')->first(),
        ]);
    }

    /**
     * Indicate that the model type is expense.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function expense()
    {
        return $this->state([
            'type' => 'expense',
            'category_id' => $this->company->categories()->expense()->get()->random(1)->pluck('id')->first(),
        ]);
    }

    /**
     * Indicate that the model is recurring.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function recurring()
    {
        return $this->state([
            'type' => $this->getRawAttribute('type') . '-recurring',
            'number' => $this->getNextTransactionNumber('-recurring'),
            'recurring_started_at' => $this->getRawAttribute('paid_at'),
            'recurring_frequency' => 'daily',
            'recurring_custom_frequency' => 'daily',
            'recurring_interval' => '1',
            'recurring_limit' => 'date',
            'recurring_limit_date' => Date::now()->addDay(7)->format('Y-m-d'),
            'disabled_transaction_paid' => "Auto-generated",
            'disabled_transaction_number' => "Auto-generated",
            'real_type' => $this->getRawAttribute('type'),
        ]);
    }
}
