<?php

namespace Laratrust\Checkers\Role;

use Illuminate\Database\Eloquent\Model;

abstract class LaratrustRoleChecker
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $role;

    public function __construct(Model $role)
    {
        $this->role = $role;
    }

    abstract public function currentRoleHasPermission($permission, $requireAll = false);

    abstract public function currentRoleFlushCache();
}
