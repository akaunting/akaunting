<?php

use App\Models\Auth\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    $map = ['Create', 'Read', 'Update', 'Delete'];

    $prefix = $faker->randomElement($map);

    return [
        'name' => strtolower($prefix) . '-' . strtolower($faker->word) . '-' . strtolower($faker->word),
        'display_name' => $prefix . ' ' . $faker->text(5),
        'description' => $prefix . ' ' . $faker->text(5),
    ];
});
