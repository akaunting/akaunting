<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Auth\User;
use App\Models\Common\Company as Model;
use Illuminate\Support\Facades\Artisan;

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
            'enabled' => $this->faker->boolean ? 1 : 0,
            'created_from' => 'core::factory',
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
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Model $company) {
            $company->makeCurrent();

            app()->setLocale('en-GB');

            // Company seeds
            Artisan::call('company:seed', [
                'company' => $company->id
            ]);

            $user = User::first();

            $user->companies()->attach($company->id);

            // User seeds
            Artisan::call('user:seed', [
                'user' => $user->id,
                'company' => $company->id,
            ]);

            setting()->set([
				'company.name' => $this->faker->text(15),
                'company.address' => 'New Street 1254',
                'company.city' => 'London',
                'company.country' => $this->faker->countryCode,
                'default.currency' => 'USD',
                'default.locale' => 'en-GB',
            ]);

            setting()->save();
        });
    }
}
