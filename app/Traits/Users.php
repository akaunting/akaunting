<?php

namespace App\Traits;

use App\Models\Auth\User;

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

        if (in_array($id, $companies)) {
            return true;
        }

        return false;
    }
}
