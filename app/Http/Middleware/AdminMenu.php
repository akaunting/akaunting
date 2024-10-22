<?php

namespace App\Http\Middleware;

use App\Events\Menu\AdminCreated;
use App\Events\Menu\AdminCreating;
use Closure;

class AdminMenu
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

        menu()->create('admin', function ($menu) {
            $menu->style('tailwind');

            event(new AdminCreating($menu));

            event(new AdminCreated($menu));
        });

        return $next($request);
    }
}
