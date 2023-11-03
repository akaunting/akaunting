<?php

namespace App\Traits;

use App\Traits\Users;

trait Companies
{
    use Users;

    public $request = null;

    public function getCompanyId()
    {
        if ($company_id = company_id()) {
            return $company_id;
        }

        $request = $this->request ?: request();

        if (request_is_api($request)) {
            return $this->getCompanyIdFromApi($request);
        }

        return $this->getCompanyIdFromWeb($request);
    }

    public function getCompanyIdFromWeb($request)
    {
        return $this->getCompanyIdFromRoute($request) ?: ($this->getCompanyIdFromQuery($request) ?: $this->getCompanyIdFromHeader($request));
    }

    public function getCompanyIdFromApi($request)
    {
        $company_id = $this->getCompanyIdFromQuery($request) ?: $this->getCompanyIdFromHeader($request);

        return $company_id ?: $this->getFirstCompanyOfUser()?->id;
    }

    public function getCompanyIdFromRoute($request)
    {
        $route_id = (int) $request->route('company_id');
        $segment_id = (int) $request->segment(1);

        return $route_id ?: $segment_id;
    }

    public function getCompanyIdFromQuery($request)
    {
        return (int) $request->query('company_id');
    }

    public function getCompanyIdFromHeader($request)
    {
        return (int) $request->header('X-Company');
    }
}
