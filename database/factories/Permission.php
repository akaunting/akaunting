<?php

use App\Models\Auth\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    $map = ['Create', 'Read', 'Update', 'Delete'];

    $prefix = $faker->randomElement($map);
    $word_1 = $faker->word;
    $word_2 = $faker->word;

    return [
        'name' => strtolower($prefix) . '-' . strtolower($word_1) . '-' . strtolower($word_2),
        'display_name' => $prefix . ' ' . $word_1 . ' ' . $word_2,
        'description' => $prefix . ' ' . $word_1 . ' ' . $word_2,
    ];
});
