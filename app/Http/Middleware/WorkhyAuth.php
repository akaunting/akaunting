<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Crypt;

class WorkhyAuth
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $key = Config::get('workhy.auth.signed_key_name');
        $guards = Config::get('workhy.auth.guards');

        if(!$token = $this->getToken($request, $key)){
            return $response;
        }

        $request->headers->set('Authorization', 'Bearer '. Crypt::decryptString($token));

        $user = Auth::guard('sanctum')->user();

        if(!$user || ($user && !$user->enabled)){
            $this->invalidate($request);

            return $response;
        }

        $company = $user->withoutEvents(function () use ($user) {
            return $user->companies()->enabled()->first();
        });

        if(!$company){
            $this->invalidate($request);

            return $response;
        }

        Auth::login($user);

        return redirect()->route($user->landing_page, [
            'company_id' => $company->id
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param  string $key
     * @return bool
     */
    protected function getToken(Request $request, string $key): bool|string
    {
        if(!$request->session()->has($key)){
            if(!$request->has($key)){
                return false;
            }

            $request->session()->put($key, $request->get($key));
        }

        return $request->session()->get($key);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    protected function invalidate(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->getHandler()->destroy($request->session()->getId());
    }
}
