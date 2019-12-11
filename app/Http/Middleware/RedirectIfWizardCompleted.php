<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class RedirectIfWizardCompleted
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
        // Check wizard is completed or not
        if (setting('wizard.completed', 0) == 1) {
            return $next($request);
        }
        
        // Already in the wizard
        if (Str::startsWith($request->getPathInfo(), '/wizard')) {
            return $next($request);
        }
        
        // Not wizard completed, redirect to wizard
        redirect()->route('wizard.edit')->send();
    }
}
