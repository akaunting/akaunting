<?php

namespace Laratrust\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustPermissionTrait;
use Laratrust\Contracts\LaratrustPermissionInterface;

class LaratrustPermission extends Model implements LaratrustPermissionInterface
{
    use LaratrustPermissionTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('laratrust.tables.permissions');
    }
}
