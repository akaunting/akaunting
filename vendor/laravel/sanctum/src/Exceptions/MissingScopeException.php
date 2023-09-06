<?php

namespace Laravel\Sanctum\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Arr;

/**
 * @deprecated
 * @see \Laravel\Sanctum\Exceptions\MissingAbilityException
 */
class MissingScopeException extends AuthorizationException
{
    /**
     * The scopes that the user did not have.
     *
     * @var array
     */
    protected $scopes;

    /**
     * Create a new missing scope exception.
     *
     * @param  array|string  $scopes
     * @param  string  $message
     * @return void
     */
    public function __construct($scopes = [], $message = 'Invalid scope(s) provided.')
    {
        parent::__construct($message);

        $this->scopes = Arr::wrap($scopes);
    }

    /**
     * Get the scopes that the user did not have.
     *
     * @return array
     */
    public function scopes()
    {
        return $this->scopes;
    }
}
