<?php

namespace Sentry\Laravel\Http;

use Closure;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Sentry\State\HubInterface;
use Sentry\State\Scope;

/**
 * This middleware enriches the Sentry scope with the IP address of the request.
 * We do this ourself instead of letting the PHP SDK handle this because we want
 * the IP from the Laravel request because it takes into account trusted proxies.
 */
class SetRequestIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $container = Container::getInstance();

        if ($container->bound(HubInterface::class)) {
            /** @var \Sentry\State\HubInterface $sentry */
            $sentry = $container->make(HubInterface::class);

            $client = $sentry->getClient();

            if ($client !== null && $client->getOptions()->shouldSendDefaultPii()) {
                $sentry->configureScope(static function (Scope $scope) use ($request): void {
                    $scope->setUser([
                        'ip_address' => $request->ip(),
                    ]);
                });
            }
        }

        return $next($request);
    }
}
