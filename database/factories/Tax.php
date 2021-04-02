<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Setting\Tax as Model;

class Tax extends Factory
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
        $types = ['normal', 'inclusive', 'compound', 'fixed', 'withholding'];

        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'rate' => $this->faker->randomFloat(2, 10, 20),
            'type' => $this->faker->randomElement($types),
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
     * Indicate that the model type is normal.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function normal()
    {
        return $this->state([
            'type' => 'normal',
        ]);
    }

    /**
     * Indicate that the model type is inclusive.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inclusive()
    {
        return $this->state([
            'type' => 'inclusive',
        ]);
    }

    /**
     * Indicate that the model type is compound.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function compound()
    {
        return $this->state([
            'type' => 'compound',
        ]);
    }

    /**
     * Indicate that the model type is fixed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function fixed()
    {
        return $this->state([
            'type' => 'fixed',
        ]);
    }

    /**
     * Indicate that the model type is normal.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withholding()
    {
        return $this->state([
            'type' => 'withholding',
        ]);
    }
}
