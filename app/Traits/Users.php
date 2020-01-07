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

        $companies = $user->companies()->pluck('id')->toArray();

        return in_array($id, $companies);
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

        $dashboards = $user->dashboards()->pluck('id')->toArray();

        return in_array($id, $dashboards);
    }
}
