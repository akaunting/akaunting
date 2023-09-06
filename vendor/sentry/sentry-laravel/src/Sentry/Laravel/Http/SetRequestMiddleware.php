<?php

namespace Sentry\Laravel\Http;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;
use Sentry\State\HubInterface;
use Throwable;

/**
 * This middleware caches a PSR-7 version of the request as early as possible.
 * This is done to prevent running into (mostly uploaded file) parsing failures.
 */
class SetRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $container = Container::getInstance();

        if ($container->bound(HubInterface::class)) {
            $psrRequest = $this->resolvePsrRequest($container);

            if ($psrRequest !== null) {
                $container->instance(LaravelRequestFetcher::CONTAINER_PSR7_INSTANCE_KEY, $psrRequest);
            }
        }

        return $next($request);
    }

    private function resolvePsrRequest(Container $container): ?ServerRequestInterface
    {
        try {
            return $container->make(ServerRequestInterface::class);
        } catch (Throwable $e) {
            // Do not crash if there is an exception thrown while resolving the request object
        }

        return null;
    }
}
