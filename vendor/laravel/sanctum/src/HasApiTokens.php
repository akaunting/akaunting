<?php

namespace Laravel\Sanctum;

use DateTimeInterface;
use Illuminate\Support\Str;

trait HasApiTokens
{
    /**
     * The access token the user is using for the current request.
     *
     * @var \Laravel\Sanctum\Contracts\HasAbilities
     */
    protected $accessToken;

    /**
     * Get the access tokens that belong to model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function tokens()
    {
        return $this->morphMany(Sanctum::$personalAccessTokenModel, 'tokenable');
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param  string  $ability
     * @return bool
     */
    public function tokenCan(string $ability)
    {
        return $this->accessToken && $this->accessToken->can($ability);
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $abilities
     * @param  \DateTimeInterface|null  $expiresAt
     * @return \Laravel\Sanctum\NewAccessToken
     */
    public function createToken(string $name, array $abilities = ['*'], DateTimeInterface $expiresAt = null)
    {
        $plainTextToken = sprintf(
            '%s%s%s',
            config('sanctum.token_prefix', ''),
            $tokenEntropy = Str::random(40),
            hash('crc32b', $tokenEntropy)
        );

        $token = $this->tokens()->create([
            'name' => $name,
            'token' => hash('sha256', $plainTextToken),
            'abilities' => $abilities,
            'expires_at' => $expiresAt,
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$plainTextToken);
    }

    /**
     * Get the access token currently associated with the user.
     *
     * @return \Laravel\Sanctum\Contracts\HasAbilities
     */
    public function currentAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set the current access token for the user.
     *
     * @param  \Laravel\Sanctum\Contracts\HasAbilities  $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
