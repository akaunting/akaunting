<?php

namespace Database\Factories\OAuth;

use App\Abstracts\Factory;
use App\Models\OAuth\AccessToken as Model;
use Illuminate\Support\Str;

class AccessToken extends Factory
{
    protected $model = Model::class;

    public function definition(): array
    {
        return [
            'id'           => Str::random(100),
            'company_id'   => $this->company->id,
            'user_id'      => $this->user->id,
            'client_id'    => null, // set via for()
            'name'         => $this->faker->words(2, true),
            'scopes'       => [],
            'revoked'      => false,
            'audience'     => url('/'),
            'created_from' => 'core::factory',
            'expires_at'   => now()->addHour(),
        ];
    }

    public function revoked(): static
    {
        return $this->state(['revoked' => true]);
    }

    public function expired(): static
    {
        return $this->state(['expires_at' => now()->subDay()]);
    }

    public function withScopes(array $scopes): static
    {
        return $this->state(['scopes' => $scopes]);
    }

    public function forClient(int $clientId): static
    {
        return $this->state(['client_id' => $clientId]);
    }

    public function audience(string $audience): static
    {
        return $this->state(['audience' => $audience]);
    }
}
