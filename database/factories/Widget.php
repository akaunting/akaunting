<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Common\Dashboard;
use App\Models\Common\Widget as Model;

class Widget extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    public $classes = [
        'App\Widgets\TotalIncome',
        'App\Widgets\TotalExpenses',
        'App\Widgets\TotalProfit',
        'App\Widgets\CashFlow',
        'App\Widgets\IncomeByCategory',
        'App\Widgets\ExpensesByCategory',
        'App\Widgets\AccountBalance',
        'App\Widgets\LatestIncome',
        'App\Widgets\LatestExpenses',
        'App\Widgets\Currencies',
    ];

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
            'class' => $this->faker->randomElement($this->classes),
        ];
    }
}
