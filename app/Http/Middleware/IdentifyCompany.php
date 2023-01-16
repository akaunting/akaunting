<?php

namespace App\Http\Middleware;

use App\Traits\Users;
use Closure;
use Illuminate\Auth\AuthenticationException;

class IdentifyCompany
{
    use Users;

    public $request;

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
        $this->request = $request;

        $company_id = $this->getCompanyId();

        if (empty($company_id)) {
            abort(500, 'Missing company');
        }

        // Check if user can access company
        if ($this->request->isNotSigned($company_id) && $this->isNotUserCompany($company_id)) {
            throw new AuthenticationException('Unauthenticated.', $guards);
        }

        // Set company as current
        $company = company($company_id);

        if (empty($company)) {
            abort(500, 'Company not found');
        }

        $company->makeCurrent();

        // Fix file/folder paths
        config(['filesystems.disks.' . config('filesystems.default') . '.url' => url('/' . $company_id)  . '/uploads']);

        // Fix routes
        if ($this->request->isNotApi()) {
            app('url')->defaults(['company_id' => $company_id]);
            $this->request->route()->forgetParameter('company_id');
        }

        return $next($this->request);
    }

    protected function getCompanyId()
    {
        if ($company_id = company_id()) {
            return $company_id;
        }

        if ($this->request->isApi()) {
            return $this->getCompanyIdFromApi();
        }

        return $this->getCompanyIdFromWeb();
    }

    protected function getCompanyIdFromWeb()
    {
        return $this->getCompanyIdFromRoute() ?: ($this->getCompanyIdFromQuery() ?: $this->getCompanyIdFromHeader());
    }

    protected function getCompanyIdFromApi()
    {
        $company_id = $this->getCompanyIdFromQuery() ?: $this->getCompanyIdFromHeader();

        return $company_id ?: $this->getFirstCompanyOfUser()?->id;
    }

    protected function getCompanyIdFromRoute()
    {
        return (int) $this->request->route('company_id');
    }

    protected function getCompanyIdFromQuery()
    {
        return (int) $this->request->query('company_id');
    }

    protected function getCompanyIdFromHeader()
    {
        return (int) $this->request->header('X-Company');
    }
}
