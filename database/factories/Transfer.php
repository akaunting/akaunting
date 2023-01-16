<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Banking\Account;
use App\Models\Banking\Transfer as Model;

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
        $from_account = Account::factory()->enabled()->default_currency()->create();

        $to_account = Account::factory()->enabled()->default_currency()->create();

        return [
            'company_id' => $this->company->id,
            'from_account_id' => $from_account->id,
            'to_account_id' => $to_account->id,
            'amount' => $this->faker->randomFloat(2, 1, 1000),
            'transferred_at' => $this->faker->date(),
            'description'=> $this->faker->text(20),
            'payment_method' => setting('default.payment_method'),
            'reference' => $this->faker->text(20),
            'created_from' => 'core::factory',
        ];
    }
}
