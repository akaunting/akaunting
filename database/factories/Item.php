<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Common\Item as Model;

class Item extends Factory
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
            'description' => $this->faker->text(100),
            'purchase_price' => $this->faker->randomFloat(2, 10, 20),
            'sale_price' => $this->faker->randomFloat(2, 10, 20),
            'category_id' => $this->company->categories()->item()->get()->random(1)->pluck('id')->first(),
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
