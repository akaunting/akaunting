<?php

namespace App\Traits;

use Illuminate\Container\Container;

trait HasApiTokens
{
    /**
     * The current access token for the authentication user.
     *
     * @var \Laravel\Passport\Token|\Laravel\Passport\TransientToken|null
     */
    protected $accessToken;

    /**
     * Get all of the user's registered OAuth clients.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function clients()
    {
        return $this->hasMany(\Laravel\Passport\Passport::clientModel(), 'user_id');
    }

    /**
     * Get all of the access tokens for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(\Laravel\Passport\Passport::tokenModel(), 'user_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the current access token being used by the user.
     *
     * @return \Laravel\Passport\Token|\Laravel\Passport\TransientToken|null
     */
    public function token()
    {
        return $this->accessToken;
    }

    /**
     * Determine if the current API token has a given scope.
     *
     * @param  string  $scope
     * @return bool
     */
    public function tokenCan($scope)
    {
        return $this->accessToken ? $this->accessToken->can($scope) : false;
    }

    /**
     * Create a new personal access token for the user.
     *
     * @param  string  $name
     * @param  array  $scopes
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    public function createToken($name, array $scopes = [])
    {
        return Container::getInstance()->make(\Laravel\Passport\PersonalAccessTokenFactory::class)->make(
            $this->getKey(), $name, $scopes
        );
    }

    /**
     * Set the current access token for the user.
     *
     * @param  \Laravel\Passport\Token|\Laravel\Passport\TransientToken|null  $accessToken
     * @return $this
     */
    public function withAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
