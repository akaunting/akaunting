<?php

namespace Database\Factories\OAuth;

use App\Abstracts\Factory;
use App\Models\OAuth\Scope as Model;

class Scope extends Factory
{
    protected $model = Model::class;

    public function definition(): array
    {
        $key = strtolower($this->faker->unique()->word) . ':' . $this->faker->randomElement(['read', 'write', 'manage']);

        return [
            'key'          => $key,
            'name'         => $this->faker->words(2, true),
            'description'  => $this->faker->sentence,
            'group'        => $this->faker->randomElement(['basic', 'advanced', 'mcp', 'custom']),
            'enabled'      => true,
            'is_default'   => false,
            'sort_order'   => $this->faker->numberBetween(10, 100),
            'created_from' => 'core::factory',
        ];
    }

    public function enabled(): static
    {
        return $this->state(['enabled' => true]);
    }

    public function disabled(): static
    {
        return $this->state(['enabled' => false]);
    }

    public function default(): static
    {
        return $this->state(['is_default' => true]);
    }

    public function mcp(): static
    {
        return $this->state(['group' => 'mcp']);
    }
}
