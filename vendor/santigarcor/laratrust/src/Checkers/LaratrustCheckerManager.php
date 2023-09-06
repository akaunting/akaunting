<?php

namespace Laratrust\Checkers;

use Illuminate\Support\Facades\Config;
use Laratrust\Checkers\Role\LaratrustRoleQueryChecker;
use Laratrust\Checkers\User\LaratrustUserQueryChecker;
use Laratrust\Checkers\Role\LaratrustRoleDefaultChecker;
use Laratrust\Checkers\User\LaratrustUserDefaultChecker;

class LaratrustCheckerManager
{
    /**
     * The object in charge of checking the roles and permissions.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Return the right checker according to the configuration.
     *
     * @return \Laratrust\Checkers\LaratrustChecker
     */
    public function getUserChecker()
    {
        switch (Config::get('laratrust.checker', 'default')) {
            case 'default':
                return new LaratrustUserDefaultChecker($this->model);
            case 'query':
                return new LaratrustUserQueryChecker($this->model);
        }
    }

    /**
     * Return the right checker according to the configuration.
     *
     * @return \Laratrust\Checkers\LaratrustChecker
     */
    public function getRoleChecker()
    {
        switch (Config::get('laratrust.checker', 'default')) {
            case 'default':
                return new LaratrustRoleDefaultChecker($this->model);
            case 'query':
                return new LaratrustRoleQueryChecker($this->model);
        }
    }
}
