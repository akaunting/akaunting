<?php

namespace App\Http\Middleware;

use App\Traits\Users;
use Closure;
use Illuminate\Auth\AuthenticationException;

class IdentifyCompany
{
    use Users;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $company_id = $request->isApi()
                        ? $this->getCompanyIdFromApi($request)
                        : $this->getCompanyIdFromWeb($request);

        if (empty($company_id)) {
            abort(500, 'Missing company');
        }

        // Check if user can access company
        if ($request->isNotSigned($company_id) && $this->isNotUserCompany($company_id)) {
            throw new AuthenticationException('Unauthenticated.', $guards);
        }

        // Set company as current
        company($company_id)->makeCurrent();

        // Fix file/folder paths
        config(['filesystems.disks.' . config('filesystems.default') . '.url' => url('/' . $company_id)  . '/uploads']);

        // Fix routes
        app('url')->defaults(['company_id' => $company_id]);
        $request->route()->forgetParameter('company_id');

        return $next($request);
    }

    protected function getCompanyIdFromWeb($request)
    {
        return (int) $request->route('company_id');
    }

    protected function getCompanyIdFromApi($request)
    {
        $company_id = $request->get('company_id', $request->header('X-Company'));

        return $company_id ?: optional($this->getFirstCompanyOfUser())->id;
    }
}
