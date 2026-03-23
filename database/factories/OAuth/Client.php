<?php

namespace Database\Factories\OAuth;

use App\Abstracts\Factory;
use App\Models\OAuth\Client as Model;
use Illuminate\Support\Str;

class Client extends Factory
{
    protected $model = Model::class;

    public function definition(): array
    {
        return [
            'company_id'             => $this->company->id,
            'user_id'                => $this->user->id,
            'name'                   => $this->faker->company . ' App',
            'secret'                 => Str::random(40),
            'redirect'               => 'https://example.com/callback',
            'personal_access_client' => false,
            'password_client'        => false,
            'revoked'                => false,
            'created_from'           => 'core::factory',
        ];
    }

    public function confidential(): static
    {
        return $this->state(['secret' => Str::random(40)]);
    }

    public function public(): static
    {
        return $this->state(['secret' => null]);
    }

    public function personalAccess(): static
    {
        return $this->state([
            'personal_access_client' => true,
            'redirect'               => 'http://localhost',
        ]);
    }

    public function revoked(): static
    {
        return $this->state(['revoked' => true]);
    }
}
