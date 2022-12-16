<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Common\Company as Model;

class Company extends Factory
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
            'name' => $this->faker->company,
            'email' => $this->faker->freeEmail,
            'currency' => $this->faker->randomElement(array_keys(\Akaunting\Money\Currency::getCurrencies())),
            'country' => $this->faker->randomElement(array_keys(trans('countries'))),
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
}
