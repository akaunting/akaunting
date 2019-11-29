<?php

namespace App\Http\Middleware;

use App\Events\Menu\PortalCreated;
use Closure;

class PortalMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if logged in
        if (!auth()->check()) {
            return $next($request);
        }

        menu()->create('portal', function ($menu) {
            $menu->style('argon');

            event(new PortalCreated($menu));
        });

        return $next($request);
    }
}
