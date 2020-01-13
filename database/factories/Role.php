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
        'permissions' => Permission::take(10)->pluck('id')->toArray(),
    ];
});
