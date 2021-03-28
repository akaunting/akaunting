<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Common\Contact as Model;
use App\Traits\Contacts;

class Contact extends Factory
{
    use Contacts;

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
        $types = array_merge($this->getCustomerTypes(), $this->getVendorTypes());

        return [
            'company_id' => $this->company->id,
            'type' => $this->faker->randomElement($types),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'user_id' => null,
            'tax_number' => $this->faker->randomNumber(9),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'website' => 'https://akaunting.com',
            'currency_code' => setting('default.currency'),
            'reference' => $this->faker->text(5),
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
     * Indicate that the model type is customer.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function customer()
    {
        return $this->state([
            'type' => 'customer',
        ]);
    }

    /**
     * Indicate that the model type is vendor.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function vendor()
    {
        return $this->state([
            'type' => 'vendor',
        ]);
    }
}
