<?php

namespace Sentry\Laravel\Http;

use Illuminate\Container\Container;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Sentry\Integration\RequestFetcher;
use Sentry\Integration\RequestFetcherInterface;

class LaravelRequestFetcher implements RequestFetcherInterface
{
    /**
     * They key in the container where a PSR-7 instance of the current request could be stored.
     */
    public const CONTAINER_PSR7_INSTANCE_KEY = 'sentry-laravel.psr7.request';

    public function fetchRequest(): ?ServerRequestInterface
    {
        $container = Container::getInstance();

        // If there is no request bound to the container
        // we are not dealing with a HTTP request and there
        // is no request to fetch for us so we can exit early.
        if (!$container->bound('request')) {
            return null;
        }

        if ($container->bound(self::CONTAINER_PSR7_INSTANCE_KEY)) {
            $request = $container->make(self::CONTAINER_PSR7_INSTANCE_KEY);
        } else {
            $request = (new RequestFetcher)->fetchRequest();
        }

        if ($request === null) {
            return null;
        }

        $cookies = new Collection($request->getCookieParams());

        // We need to filter out the cookies that are not allowed to be sent to Sentry because they are very sensitive
        $forbiddenCookies = [config('session.cookie'), 'remember_*'];

        return $request->withCookieParams(
            $cookies->map(function ($value, string $key) use ($forbiddenCookies) {
                if (Str::is($forbiddenCookies, $key)) {
                    return '[Filtered]';
                }

                return $value;
            })->all()
        );
    }
}
