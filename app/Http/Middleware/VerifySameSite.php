<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifySameSite
{
    /**
     * The GET routes that change data, which a link on another site should not trigger.
     * The routes of the modules named the same way are covered too.
     *
     * @var array
     */
    protected $routes = [
        '*.enable',
        '*.disable',
        '*.sent',
        '*.received',
        '*.cancelled',
        '*.restore',
        '*.duplicate',
        '*.end',
        '*.clear',
        '*.uninstall',
        'updates.run',
        'logout',
        '*.logout',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isMethod('get') || ! $request->routeIs($this->routes)) {
            return $next($request);
        }

        if ($this->isCrossSite($request)) {
            abort(403);
        }

        return $next($request);
    }

    /**
     * The browsers tell where the request comes from with Sec-Fetch-Site, the older ones
     * with the Referer only. A request with neither, a bookmark for example, is let through.
     */
    protected function isCrossSite(Request $request): bool
    {
        $site = $request->headers->get('Sec-Fetch-Site');

        if (! empty($site)) {
            return $site === 'cross-site';
        }

        $referer = $request->headers->get('Referer');

        if (empty($referer)) {
            return false;
        }

        return parse_url($referer, PHP_URL_HOST) !== $request->getHost();
    }
}
