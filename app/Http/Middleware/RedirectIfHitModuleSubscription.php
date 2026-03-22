<?php

namespace App\Http\Middleware;

use App\Traits\Modules;
use App\Utilities\Versions;
use Closure;

class RedirectIfHitModuleSubscription
{
    use Modules;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->isMethod(strtolower('GET'))) {
            return $next($request);
        }

        if ($request->ajax()) {
            return $next($request);
        }

        if ($request->is(company_id() . '/apps/*')) {
            return $next($request);
        }

        if (! $this->getModulesLimitOfSubscription()->action_status) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
