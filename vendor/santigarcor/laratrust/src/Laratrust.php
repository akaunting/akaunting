<?php

namespace Laratrust;

/**
 * This class is the main entry point of laratrust. Usually this the interaction
 * with this class will be done through the Laratrust Facade
 *
 * @license MIT
 * @package Laratrust
 */
class Laratrust
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new confide instance.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Checks if the current user has a role by its name.
     *
     * @param  string  $role  Role name.
     * @return bool
     */
    public function hasRole($role, $team = null, $requireAll = false)
    {
        if ($user = $this->user()) {
            return $user->hasRole($role, $team, $requireAll);
        }

        return false;
    }

    /**
     * Check if the current user has a permission by its name.
     *
     * @param  string  $permission Permission string.
     * @return bool
     */
    public function isAbleTo($permission, $team = null, $requireAll = false)
    {
        if ($user = $this->user()) {
            return $user->hasPermission($permission, $team, $requireAll);
        }

        return false;
    }

    /**
     * Check if the current user has a role or permission by its name.
     *
     * @param  array|string  $roles            The role(s) needed.
     * @param  array|string  $permissions      The permission(s) needed.
     * @param  array  $options                 The Options.
     * @return bool
     */
    public function ability($roles, $permissions, $team = null, $options = [])
    {
        if ($user = $this->user()) {
            return $user->ability($roles, $permissions, $team, $options);
        }

        return false;
    }

    /**
     * Checks if the user owns the thing.
     *
     * @param  Object  $thing
     * @param  string  $foreignKeyName
     * @return boolean
     */
    public function owns($thing, $foreignKeyName = null)
    {
        if ($user = $this->user()) {
            return $user->owns($thing, $foreignKeyName);
        }

        return false;
    }

    /**
     * Checks if the user has some role and if he owns the thing.
     *
     * @param  string|array  $role
     * @param  Object  $thing
     * @param  array  $options
     * @return boolean
     */
    public function hasRoleAndOwns($role, $thing, $options = [])
    {
        if ($user = $this->user()) {
            return $user->hasRoleAndOwns($role, $thing, $options);
        }

        return false;
    }

    /**
     * Checks if the user can do something and if he owns the thing.
     *
     * @param  string|array  $permission
     * @param  Object  $thing
     * @param  array  $options
     * @return boolean
     */
    public function isAbleToAndOwns($permission, $thing, $options = [])
    {
        if ($user = $this->user()) {
            return $user->isAbleToAndOwns($permission, $thing, $options);
        }

        return false;
    }

    /**
     * Get the currently authenticated user or null.
     *
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function user()
    {
        return $this->app->auth->user();
    }
}
