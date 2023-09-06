<?php

namespace Laratrust;

use InvalidArgumentException;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

class Helper
{
    /**
     * Gets the id from an array, object or integer.
     *
     * @param  mixed  $object
     * @param  string  $type
     * @return int
     */
    public static function getIdFor($object, string $type)
    {
        if (is_null($object)) {
            return null;
        }

        if (is_object($object)) {
            return $object->getKey();
        }

        if (is_array($object)) {
            return $object['id'];
        }

        if (is_numeric($object)) {
            return $object;
        }

        if (is_string($object)) {
            return call_user_func_array([
                Config::get("laratrust.models.{$type}"), 'where'
            ], ['name', $object])->firstOrFail()->getKey();
        }

        throw new InvalidArgumentException(
            'getIdFor function only accepts an integer, a Model object or an array with an "id" key'
        );
    }

    /**
     * Check if a string is a valid relationship name.
     *
     * @param string $relationship
     * @return boolean
     */
    public static function isValidRelationship($relationship)
    {
        return in_array($relationship, ['roles', 'permissions']);
    }

    /**
     * Returns the team's foreign key.
     *
     * @return string
     */
    public static function teamForeignKey()
    {
        return Config::get('laratrust.foreign_keys.team');
    }

    /**
     * Fetch the team model from the name.
     *
     * @param  mixed  $team
     * @return mixed
     */
    public static function fetchTeam($team = null)
    {
        if (is_null($team) || !Config::get('laratrust.teams.enabled')) {
            return null;
        }

        return static::getIdFor($team, 'team');
    }

    /**
     * Assing the real values to the team and requireAllOrOptions parameters.
     *
     * @param  mixed  $team
     * @param  mixed  $requireAllOrOptions
     * @return array
     */
    public static function assignRealValuesTo($team, $requireAllOrOptions, $method)
    {
        return [
            ($method($team) ? null : $team),
            ($method($team) ? $team : $requireAllOrOptions),
        ];
    }

    /**
     * Checks if the string passed contains a pipe '|' and explodes the string to an array.
     * @param  string|array  $value
     * @return string|array
     */
    public static function standardize($value, $toArray = false)
    {
        if (is_array($value) || ((strpos($value, '|') === false) && !$toArray)) {
            return $value;
        }

        return explode('|', $value);
    }

    /**
     * Check if a role or permission is attach to the user in a same team.
     *
     * @param  mixed  $rolePermission
     * @param  \Illuminate\Database\Eloquent\Model  $team
     * @return boolean
     */
    public static function isInSameTeam($rolePermission, $team)
    {
        if (
            !Config::get('laratrust.teams.enabled')
            || (!Config::get('laratrust.teams.strict_check') && is_null($team))
        ) {
            return true;
        }

        $teamForeignKey = static::teamForeignKey();

        return $rolePermission['pivot'][$teamForeignKey] == $team;
    }

    /**
     * Checks if the option exists inside the array,
     * otherwise, it sets the first option inside the default values array.
     *
     * @param  string  $option
     * @param  array  $array
     * @param  array  $possibleValues
     * @return array
     */
    public static function checkOrSet($option, $array, $possibleValues)
    {
        if (!isset($array[$option])) {
            $array[$option] = $possibleValues[0];

            return $array;
        }

        $ignoredOptions = ['team', 'foreignKeyName'];

        if (!in_array($option, $ignoredOptions) && !in_array($array[$option], $possibleValues, true)) {
            throw new InvalidArgumentException();
        }

        return $array;
    }

    /**
     * Creates a model from an array filled with the class data.
     *
     * @param string $class
     * @param string|\Illuminate\Database\Eloquent\Model $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function hidrateModel($class, $data)
    {
        if ($data instanceof Model) {
            return $data;
        }

        if (!isset($data['pivot'])) {
            throw new \Exception("The 'pivot' attribute in the {$class} is hidden");
        }

        $model = new $class;
        $primaryKey = $model->getKeyName();

        $model->setAttribute($primaryKey, $data[$primaryKey])->setAttribute('name', $data['name']);
        $model->setRelation(
            'pivot',
            MorphPivot::fromRawAttributes($model, $data['pivot'], 'pivot_table')
        );

        return $model;
    }

    /**
     * Return two arrays with the filtered permissions between the permissions
     * with wildcard and the permissions without it.
     *
     * @param array $permissions
     * @return array [$wildcard, $noWildcard]
     */
    public static function getPermissionWithAndWithoutWildcards($permissions)
    {
        $wildcard = [];
        $noWildcard = [];

        foreach ($permissions as $permission) {
            if (strpos($permission, '*') === false) {
                $noWildcard[] = $permission;
            } else {
                $wildcard[] = str_replace('*', '%', $permission);
            }
        }

        return [$wildcard, $noWildcard];
    }

    /**
     * Check if a role is editable in the admin panel.
     *
     * @param string|\Laratrust\Models\LaratrustRole $role
     * @return bool
     */
    public static function roleIsEditable($role)
    {
        $roleName = is_string($role) ? $role : $role->name;

        return ! in_array(
            $roleName,
            Config::get('laratrust.panel.roles_restrictions.not_editable') ?? []
        );
    }

    /**
     * Check if a role is deletable in the admin panel.
     *
     * @param string|\Laratrust\Models\LaratrustRole $role
     * @return bool
     */
    public static function roleIsDeletable($role)
    {
        $roleName = is_string($role) ? $role : $role->name;

        return ! in_array(
            $roleName,
            Config::get('laratrust.panel.roles_restrictions.not_deletable') ?? []
        );
    }

    /**
     * Check if a role is removable in the admin panel.
     *
     * @param string|\Laratrust\Models\LaratrustRole $role
     * @return bool
     */
    public static function roleIsRemovable($role)
    {
        $roleName = is_string($role) ? $role : $role->name;

        return ! in_array(
            $roleName,
            Config::get('laratrust.panel.roles_restrictions.not_removable') ?? []
        );
    }
}
