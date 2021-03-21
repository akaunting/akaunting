<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Setting\Category as Model;

class Category extends Factory
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
        $types = ['income', 'expense', 'item', 'other'];

        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'type' => $this->faker->randomElement($types),
            'color' => $this->faker->hexColor,
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
     * Indicate that the model type is income.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function income()
    {
        return $this->state([
            'type' => 'income',
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
        ]);
    }

    /**
     * Indicate that the model type is item.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function item()
    {
        return $this->state([
            'type' => 'item',
        ]);
    }

    /**
     * Indicate that the model type is other.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function other()
    {
        return $this->state([
            'type' => 'other',
        ]);
    }
}
