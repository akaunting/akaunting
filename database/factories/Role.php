<?php

use App\Models\Auth\Permission;
use App\Models\Auth\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    $name = $faker->word;

    return [
        'name' => strtolower($name),
        'display_name' => $name,
        'description' => $name,
    ];
});

$factory->state(Role::class, 'permissions', function (Faker $faker) {
    return [
        'permissions' => Permission::take(50)->pluck('id')->toArray(),
    ];
});

$factory->afterCreating(Role::class, function ($role, $faker) {
    $role->permissions()->attach(Permission::take(50)->pluck('id')->toArray());
});
