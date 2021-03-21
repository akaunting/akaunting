<?php

namespace Laratrust\Checkers\User;

use Laratrust\Helper;
use Illuminate\Support\Facades\Config;

class LaratrustUserQueryChecker extends LaratrustUserChecker
{
    /**
     * Checks if the user has a role by its name.
     *
     * @param  string|bool   $team      Team name.
     * @return array
     */
    public function getCurrentUserRoles($team = null)
    {
        if (config('laratrust.teams.enabled') === false) {
            return $this->user->roles->pluck('name')->toArray();
        }

        if ($team === null && config('laratrust.teams.strict_check') === false) {
            return $this->user->roles->pluck('name')->toArray();
        }

        if ($team === null) {
            return $this->user
                ->roles()
                ->wherePivot(config('laratrust.foreign_keys.team'), null)
                ->pluck('name')
                ->toArray();
        }

        $teamId = Helper::fetchTeam($team);

        return $this->user
            ->roles()
            ->wherePivot(config('laratrust.foreign_keys.team'), $teamId)
            ->pluck('name')
            ->toArray();
    }

    /**
     * Checks if the user has a role by its name.
     *
     * @param  string|array  $name       Role name or array of role names.
     * @param  string|bool   $team      Team name or requiredAll roles.
     * @param  bool          $requireAll All roles in the array are required.
     * @return bool
     */
    public function currentUserHasRole($name, $team = null, $requireAll = false)
    {
        if (empty($name)) {
            return true;
        }

        $name = Helper::standardize($name);
        $rolesNames = is_array($name) ? $name : [$name];
        list($team, $requireAll) = Helper::assignRealValuesTo($team, $requireAll, 'is_bool');
        $useTeams = Config::get('laratrust.teams.enabled');
        $teamStrictCheck = Config::get('laratrust.teams.strict_check');

        $rolesCount = $this->user->roles()
            ->whereIn('name', $rolesNames)
            ->when($useTeams && ($teamStrictCheck || !is_null($team)), function ($query) use ($team) {
                $teamId = Helper::fetchTeam($team);

                return $query->where(Config::get('laratrust.foreign_keys.team'), $teamId);
            })
            ->count();

        return $requireAll ? $rolesCount == count($rolesNames) : $rolesCount > 0;
    }

    /**
     * Check if user has a permission by its name.
     *
     * @param  string|array  $permission Permission string or array of permissions.
     * @param  string|bool  $team      Team name or requiredAll roles.
     * @param  bool  $requireAll All roles in the array are required.
     * @return bool
     */
    public function currentUserHasPermission($permission, $team = null, $requireAll = false)
    {
        if (empty($permission)) {
            return true;
        }

        $permission = Helper::standardize($permission);
        $permissionsNames = is_array($permission) ? $permission : [$permission];
        list($team, $requireAll) = Helper::assignRealValuesTo($team, $requireAll, 'is_bool');
        $useTeams = Config::get('laratrust.teams.enabled');
        $teamStrictCheck = Config::get('laratrust.teams.strict_check');

        list($permissionsWildcard, $permissionsNoWildcard) =
            Helper::getPermissionWithAndWithoutWildcards($permissionsNames);

        $rolesPermissionsCount = $this->user->roles()
            ->withCount(['permissions' =>
                function ($query) use ($permissionsNoWildcard, $permissionsWildcard) {
                    $query->whereIn('name', $permissionsNoWildcard);
                    foreach ($permissionsWildcard as $permission) {
                        $query->orWhere('name', 'like', $permission);
                    }
                }
            ])
            ->when($useTeams && ($teamStrictCheck || !is_null($team)), function ($query) use ($team) {
                $teamId = Helper::fetchTeam($team);

                return $query->where(Config::get('laratrust.foreign_keys.team'), $teamId);
            })
            ->pluck('permissions_count')
            ->sum();

        $directPermissionsCount = $this->user->permissions()
            ->whereIn('name', $permissionsNoWildcard)
            ->when($permissionsWildcard, function ($query) use ($permissionsWildcard) {
                foreach ($permissionsWildcard as $permission) {
                    $query->orWhere('name', 'like', $permission);
                }

                return $query;
            })
            ->when($useTeams && ($teamStrictCheck || !is_null($team)), function ($query) use ($team) {
                $teamId = Helper::fetchTeam($team);

                return $query->where(Config::get('laratrust.foreign_keys.team'), $teamId);
            })
            ->count();

        return $requireAll
            ? $rolesPermissionsCount + $directPermissionsCount >= count($permissionsNames)
            : $rolesPermissionsCount + $directPermissionsCount > 0;
    }

    public function currentUserFlushCache()
    {
    }
}
