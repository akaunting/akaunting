<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfWizardNotCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check setting
        if (setting('wizard.completed', 0) == 1) {
            return $next($request);
        }

        // Check url
        if ($request->isWizard(company_id()) || $request->is(company_id() . '/settings/*')) {
            return $next($request);
        }

        // Redirect to wizard
        return redirect()->route('wizard.edit');
    }
}
