<?php

namespace Database\Factories;

use App\Abstracts\Factory;
use App\Models\Auth\Permission;
use App\Models\Auth\Role as Model;

class Role extends Factory
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
        $name = $this->faker->word;

        return [
            'name' => strtolower($name),
            'display_name' => $name,
            'description' => $name,
        ];
    }

    /**
     * Indicate the model permissions.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function permissions()
    {
        return $this->state([
            'permissions' => $this->getPermissions(),
        ]);
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Model $role) {
            $role->permissions()->attach($this->getPermissions());
        });
    }

    protected function getPermissions()
    {
        return Permission::take(50)->pluck('id')->toArray();
    }
}
