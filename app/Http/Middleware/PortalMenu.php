<?php

namespace App\Http\Middleware;

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
            event(new \App\Events\Menu\PortalCreating($menu));

            $menu->style('argon');

            $user = user();

            // Dashboard
            $menu->route('portal.dashboard', trans_choice('general.dashboards', 1), [], 1, ['icon' => 'fa fa-tachometer-alt']);

            // Invoices
            $menu->route('portal.invoices.index', trans_choice('general.invoices', 2), [], 2, ['icon' => 'fa fa-money-bill']);

            // Payments
            $menu->route('portal.payments.index', trans_choice('general.payments', 2), [], 3, ['icon' => 'fa fa-shopping-cart']);

            // Transactions
            $menu->route('portal.transactions.index', trans_choice('general.transactions', 2), [], 4, ['icon' => 'fa fa-briefcase']);

            event(new \App\Events\Menu\PortalCreated($menu));
        });

        return $next($request);
    }
}
