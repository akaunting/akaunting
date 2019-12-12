<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

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
        if (Str::startsWith($request->getPathInfo(), '/wizard')) {
            return $next($request);
        }

        // Redirect to wizard
        redirect()->route('wizard.edit')->send();
    }
}
