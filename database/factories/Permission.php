<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Auth\Permission as Model;

class Permission extends Factory
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
        $map = ['Create', 'Read', 'Update', 'Delete'];

        $prefix = $this->faker->randomElement($map);
        $word_1 = $this->faker->word;
        $word_2 = $this->faker->word;

        return [
            'name' => strtolower($prefix) . '-' . strtolower($word_1) . '-' . strtolower($word_2),
            'display_name' => $prefix . ' ' . $word_1 . ' ' . $word_2,
            'description' => $prefix . ' ' . $word_1 . ' ' . $word_2,
        ];
    }
}
