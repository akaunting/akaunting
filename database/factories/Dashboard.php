<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Common\Dashboard as Model;

class Dashboard extends Factory
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
     * Indicate the model users.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function users()
    {
        return $this->state([
            'users' => $this->getCompanyUsers(),
        ]);
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Model $dashboard) {
            $dashboard->users()->attach($this->getCompanyUsers());
        });
    }
}
