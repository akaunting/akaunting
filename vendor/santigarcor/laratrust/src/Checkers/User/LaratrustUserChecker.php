<?php

namespace Laratrust\Checkers\User;

use Laratrust\Helper;
use Illuminate\Database\Eloquent\Model;

abstract class LaratrustUserChecker
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $user;

    public function __construct(Model $user)
    {
        $this->user = $user;
    }

    abstract public function currentUserHasRole($name, $team = null, $requireAll = false);

    abstract public function currentUserHasPermission($permission, $team = null, $requireAll = false);

    /**
     * Checks role(s) and permission(s).
     *
     * @param  string|array  $roles       Array of roles or comma separated string
     * @param  string|array  $permissions Array of permissions or comma separated string.
     * @param  string|bool  $team      Team name or requiredAll roles.
     * @param  array  $options     validate_all (true|false) or return_type (boolean|array|both)
     * @throws \InvalidArgumentException
     * @return array|bool
     */
    public function currentUserHasAbility($roles, $permissions, $team = null, $options = [])
    {
        list($team, $options) = Helper::assignRealValuesTo($team, $options, 'is_array');
        // Convert string to array if that's what is passed in.
        $roles = Helper::standardize($roles, true);
        $permissions = Helper::standardize($permissions, true);

        // Set up default values and validate options.
        $options = Helper::checkOrSet('validate_all', $options, [false, true]);
        $options = Helper::checkOrSet('return_type', $options, ['boolean', 'array', 'both']);

        if ($options['return_type'] == 'boolean') {
            $hasRoles = $this->currentUserHasRole($roles, $team, $options['validate_all']);
            $hasPermissions = $this->currentUserHasPermission($permissions, $team, $options['validate_all']);

            return $options['validate_all']
                ? $hasRoles && $hasPermissions
                : $hasRoles || $hasPermissions;
        }

        // Loop through roles and permissions and check each.
        $checkedRoles = [];
        $checkedPermissions = [];
        foreach ($roles as $role) {
            $checkedRoles[$role] = $this->currentUserHasRole($role, $team);
        }
        foreach ($permissions as $permission) {
            $checkedPermissions[$permission] = $this->currentUserHasPermission($permission, $team);
        }

        // If validate all and there is a false in either.
        // Check that if validate all, then there should not be any false.
        // Check that if not validate all, there must be at least one true.
        if (($options['validate_all'] && !(in_array(false, $checkedRoles) || in_array(false, $checkedPermissions))) || (!$options['validate_all'] && (in_array(true, $checkedRoles) || in_array(true, $checkedPermissions)))) {
            $validateAll = true;
        } else {
            $validateAll = false;
        }

        // Return based on option.
        if ($options['return_type'] == 'array') {
            return ['roles' => $checkedRoles, 'permissions' => $checkedPermissions];
        }

        return [$validateAll, ['roles' => $checkedRoles, 'permissions' => $checkedPermissions]];
    }

    abstract public function currentUserFlushCache();
}
