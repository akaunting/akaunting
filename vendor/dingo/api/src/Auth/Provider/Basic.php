<?php

namespace Dingo\Api\Auth\Provider;

use Dingo\Api\Routing\Route;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthManager;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Basic extends Authorization
{
    /**
     * Illuminate authentication manager.
     *
     * @var \Illuminate\Auth\AuthManager
     */
    protected $auth;

    /**
     * Basic auth identifier.
     *
     * @var string
     */
    protected $identifier;

    /**
     * Create a new basic provider instance.
     *
     * @param \Illuminate\Auth\AuthManager $auth
     * @param string                       $identifier
     *
     * @return void
     */
    public function __construct(AuthManager $auth, $identifier = 'email')
    {
        $this->auth = $auth;
        $this->identifier = $identifier;
    }

    /**
     * Authenticate request with Basic.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Dingo\Api\Routing\Route $route
     *
     * @return mixed
     */
    public function authenticate(Request $request, Route $route)
    {
        $this->validateAuthorizationHeader($request);

        if (($response = $this->auth->onceBasic($this->identifier)) && $response->getStatusCode() === 401) {
            throw new UnauthorizedHttpException('Basic', 'Invalid authentication credentials.');
        }

        return $this->auth->user();
    }

    /**
     * Get the providers authorization method.
     *
     * @return string
     */
    public function getAuthorizationMethod()
    {
        return 'basic';
    }
}
