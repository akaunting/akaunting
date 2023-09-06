<?php

namespace Laratrust\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LaratrustAbility extends LaratrustMiddleware
{

    /**
     * Handle incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure $next
     * @param  string  $roles
     * @param  string  $permissions
     * @param  string|null  $team
     * @param  string|null  $options
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions, $team = null, $options = '')
    {
        list($team, $validateAll, $guard) = $this->assignRealValuesTo($team, $options);

        if (!is_array($roles)) {
            $roles = explode(self::DELIMITER, $roles);
        }

        if (!is_array($permissions)) {
            $permissions = explode(self::DELIMITER, $permissions);
        }

        if (
            Auth::guard($guard)->guest()
            || !Auth::guard($guard)->user()
                    ->ability($roles, $permissions, $team, [
                        'validate_all' => $validateAll
                    ])
         ) {
            return $this->unauthorized();
        }

        return $next($request);
    }
}
