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

        $company = $user->companies()->where('id', $id)->first();

        return !empty($company);
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
            return false;
        }

        $dashboard = $user->dashboards()->where('id', $id)->first();

        return !empty($dashboard);
    }
}
