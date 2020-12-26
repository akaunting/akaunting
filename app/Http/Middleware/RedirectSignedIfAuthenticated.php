<?php

namespace App\Http\Middleware;

use App\Models\Document\Document;
use Closure;

class RedirectSignedIfAuthenticated
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
        if (!$user = user()) {
            return $next($request);
        }

        $prefix = $user->contact ? 'portal.' : '';
        $page = 'dashboard';
        $params = [];

        if ($request->segment(2) == 'invoices') {
            $page = 'invoices.show';

            $invoice = Document::find($request->segment(3));

            $params = [$invoice->id];
        }

        redirect()->route($prefix . $page, $params)->send();
    }
}
