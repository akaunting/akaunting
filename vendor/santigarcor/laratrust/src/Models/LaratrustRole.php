<?php

namespace Laratrust\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Laratrust\Traits\LaratrustRoleTrait;
use Laratrust\Contracts\LaratrustRoleInterface;

class LaratrustRole extends Model implements LaratrustRoleInterface
{
    use LaratrustRoleTrait;

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
        $this->table = Config::get('laratrust.tables.roles');
    }
}
