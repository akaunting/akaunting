<?php

use App\Models\Auth\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'; // password

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password,
        'password_confirmation' => $password,
        'remember_token' => Str::random(10),
        'locale' => 'en-GB',
        'companies' => ['1'],
        'roles' => ['1'],
        'enabled' => $this->faker->boolean ? 1 : 0,
    ];
});

$factory->state(User::class, 'enabled', ['enabled' => 1]);

$factory->state(User::class, 'disabled', ['enabled' => 0]);
