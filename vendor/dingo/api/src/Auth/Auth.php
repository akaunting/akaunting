<?php

namespace Dingo\Api\Auth;

use Exception;
use Dingo\Api\Routing\Router;
use Illuminate\Container\Container;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Auth
{
    /**
     * Router instance.
     *
     * @var \Dingo\Api\Routing\Router
     */
    protected $router;

    /**
     * Illuminate container instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * Array of available authentication providers.
     *
     * @var array
     */
    protected $providers;

    /**
     * The provider used for authentication.
     *
     * @var \Dingo\Api\Contract\Auth\Provider
     */
    protected $providerUsed;

    /**
     * Authenticated user instance.
     *
     * @var \Illuminate\Auth\GenericUser|\Illuminate\Database\Eloquent\Model
     */
    protected $user;

    /**
     * Create a new auth instance.
     *
     * @param \Dingo\Api\Routing\Router       $router
     * @param \Illuminate\Container\Container $container
     * @param array                           $providers
     *
     * @return void
     */
    public function __construct(Router $router, Container $container, array $providers)
    {
        $this->router = $router;
        $this->container = $container;
        $this->providers = $providers;
    }

    /**
     * Authenticate the current request.
     *
     * @param array $providers
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return mixed
     */
    public function authenticate(array $providers = [])
    {
        $exceptionStack = [];

        // Spin through each of the registered authentication providers and attempt to
        // authenticate through one of them. This allows a developer to implement
        // and allow a number of different authentication mechanisms.
        foreach ($this->filterProviders($providers) as $provider) {
            try {
                $user = $provider->authenticate($this->router->getCurrentRequest(), $this->router->getCurrentRoute());

                $this->providerUsed = $provider;

                return $this->user = $user;
            } catch (UnauthorizedHttpException $exception) {
                $exceptionStack[] = $exception;
            } catch (BadRequestHttpException $exception) {
                // We won't add this exception to the stack as it's thrown when the provider
                // is unable to authenticate due to the correct authorization header not
                // being set. We will throw an exception for this below.
            }
        }

        $this->throwUnauthorizedException($exceptionStack);
    }

    /**
     * Throw the first exception from the exception stack.
     *
     * @param array $exceptionStack
     *
     * @throws \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException
     *
     * @return void
     */
    protected function throwUnauthorizedException(array $exceptionStack)
    {
        $exception = array_shift($exceptionStack);

        if ($exception === null) {
            $exception = new UnauthorizedHttpException('dingo', 'Failed to authenticate because of bad credentials or an invalid authorization header.');
        }

        throw $exception;
    }

    /**
     * Filter the requested providers from the available providers.
     *
     * @param array $providers
     *
     * @return array
     */
    protected function filterProviders(array $providers)
    {
        if (empty($providers)) {
            return $this->providers;
        }

        return array_intersect_key($this->providers, array_flip($providers));
    }

    /**
     * Get the authenticated user.
     *
     * @param bool $authenticate
     *
     * @return \Illuminate\Auth\GenericUser|\Illuminate\Database\Eloquent\Model|null
     */
    public function getUser($authenticate = true)
    {
        if ($this->user) {
            return $this->user;
        } elseif (! $authenticate) {
            return;
        }

        try {
            return $this->user = $this->authenticate();
        } catch (Exception $exception) {
            return;
        }
    }

    /**
     * Alias for getUser.
     *
     * @param bool $authenticate
     *
     * @return \Illuminate\Auth\GenericUser|\Illuminate\Database\Eloquent\Model
     */
    public function user($authenticate = true)
    {
        return $this->getUser($authenticate);
    }

    /**
     * Set the authenticated user.
     *
     * @param \Illuminate\Auth\GenericUser|\Illuminate\Database\Eloquent\Model $user
     *
     * @return \Dingo\Api\Auth\Auth
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Check if a user has authenticated with the API.
     *
     * @param bool $authenticate
     *
     * @return bool
     */
    public function check($authenticate = false)
    {
        return ! is_null($this->user($authenticate));
    }

    /**
     * Get the provider used for authentication.
     *
     * @return \Dingo\Api\Contract\Auth\Provider
     */
    public function getProviderUsed()
    {
        return $this->providerUsed;
    }

    /**
     * Extend the authentication layer with a custom provider.
     *
     * @param string          $key
     * @param object|callable $provider
     *
     * @return void
     */
    public function extend($key, $provider)
    {
        if (is_callable($provider)) {
            $provider = call_user_func($provider, $this->container);
        }

        $this->providers[$key] = $provider;
    }
}
