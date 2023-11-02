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
        return $this->belongsTo(user_model_class());
    }

    public function role()
    {
        return $this->belongsTo(role_model_class());
    }
}
