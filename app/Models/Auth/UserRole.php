<?php

namespace App\Models\Auth;

use App\Abstracts\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';

    protected $tenantable = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'role_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Auth\Role');
    }
}
