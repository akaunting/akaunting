<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Banking\Account as Model;

class Account extends Factory
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
        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'number' => (string) $this->faker->iban(),
            'currency_code' => $this->company->currencies()->enabled()->get()->random(1)->pluck('code')->first(),
            'opening_balance' => '0',
            'bank_name' => $this->faker->text(15),
            'bank_phone' => $this->faker->phoneNumber,
            'bank_address' => $this->faker->address,
            'enabled' => $this->faker->boolean ? 1 : 0,
        ];
    }

    /**
     * Indicate that the model is enabled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function enabled()
    {
        return $this->state([
            'enabled' => 1,
        ]);
    }

    /**
     * Indicate that the model is disabled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function disabled()
    {
        return $this->state([
            'enabled' => 0,
        ]);
    }

    /**
     * Indicate that the default currency is used.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function default_currency()
    {
        return $this->state([
            'currency_code' => setting('default.currency'),
        ]);
    }
}
