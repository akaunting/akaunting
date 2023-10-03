<?php

namespace App\Models\Auth;

use App\Abstracts\Model;

class UserDashboard extends Model
{
    protected $table = 'user_dashboards';

    protected $tenantable = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'dashboard_id'];

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

    public function dashboard()
    {
        return $this->belongsTo('App\Models\Common\Dashboard');
    }

    public function dashboards()
    {
        return $this->belongsToMany('App\Models\Common\Dashboard', 'App\Models\Auth\UserDashboard');
    }
}
