<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Banking\Reconciliation as Model;
use App\Utilities\Date;

class Reconciliation extends Factory
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
        $started_at = $this->faker->dateTimeBetween(now()->startOfYear(), now()->endOfYear())->format('Y-m-d H:i:s');
        $ended_at = Date::parse($started_at)->addDays($this->faker->randomNumber(3))->format('Y-m-d H:i:s');

        return [
            'company_id' => $this->company->id,
            'account_id' => '1',
            'currency_code' => setting('default.currency'),
            'opening_balance' => '0',
            'closing_balance' => '10',
            'started_at' => $started_at,
            'ended_at' => $ended_at,
            'reconcile' => $this->faker->boolean ? 1 : 0,
        ];
    }

    /**
     * Indicate that the model is reconciled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function reconciled()
    {
        return $this->state([
            'reconcile' => 1,
        ]);
    }

    /**
     * Indicate that the model is not reconciled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function notreconciled()
    {
        return $this->state([
            'reconcile' => 0,
        ]);
    }
}
