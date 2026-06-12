<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableQuic
{
    /**
     * Handle an incoming request.
     *
     * Sends `Alt-Svc: clear` header so browsers do not attempt
     * QUIC (HTTP/3) connections, preventing ERR_QUIC_PROTOCOL_ERROR.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Alt-Svc', 'clear');

        return $response;
    }
}
