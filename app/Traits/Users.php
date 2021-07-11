<?php

namespace App\Traits;

trait Users
{
    /**
     * Get user logged in
     *
     * @return object
     */
    public function getCurrentUser()
    {
        return user();
    }

    /**
     * Check user company assignment
     *
     * @param  $id
     *
     * @return boolean
     */
    public function isUserCompany($id)
    {
        $user = user();

        if (empty($user)) {
            return false;
        }

        $company = $user->withoutEvents(function () use ($user, $id) {
            return $user->companies()->where('id', $id)->first();
        });

        return !empty($company);
    }

    public function isNotUserCompany($id)
    {
        return !$this->isUserCompany($id);
    }

    /**
     * Check user dashboard assignment
     *
     * @param  $id
     *
     * @return boolean
     */
    public function isUserDashboard($id)
    {
        $user = user();

        if (empty($user)) {
            return app()->runningInConsole() ? true : false;
        }

        $dashboard = $user->withoutEvents(function () use ($user, $id) {
            return $user->dashboards()->where('id', $id)->first();
        });

        return !empty($dashboard);
    }

    public function isNotUserDashboard($id)
    {
        return !$this->isUserDashboard($id);
    }

    /**
     * Get the fist company of active user
     *
     * @return null|\App\Models\Common\Company
     */
    public function getFirstCompanyOfUser()
    {
        $user = user();

        if (empty($user)) {
            return null;
        }

        $company = $user->withoutEvents(function () use ($user) {
            return $user->companies()->enabled()->first();
        });

        if (empty($company)) {
            return null;
        }

        return $company;
    }

    public function getLandingPageOfUser()
    {
        $user = user();

        if (empty($user)) {
            return route('login');
        }

        $route_name = $user->can('read-client-portal') ? 'portal.dashboard' : $user->landing_page;

        $company_id = company_id() ?: optional($this->getFirstCompanyOfUser())->id;

        return route($route_name, ['company_id' => $company_id]);
    }
}
