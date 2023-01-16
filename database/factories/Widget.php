<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Common\Dashboard;
use App\Models\Common\Widget as Model;
use App\Utilities\Widgets;

class Widget extends Factory
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
        $dashboard = Dashboard::first();

        return [
            'company_id' => $this->company->id,
            'dashboard_id' => $dashboard->id,
            'name' => $this->faker->text(15),
            'class' => $this->faker->randomElement(Widgets::$core_widgets),
            'created_from' => 'core::factory',
        ];
    }
}
