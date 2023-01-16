<?php

namespace App\Models\Auth;

use App\Abstracts\Model;

class UserCompany extends Model
{
    protected $table = 'user_companies';

    protected $tenantable = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'company_id'];

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

    public function company()
    {
        return $this->belongsTo('App\Models\Common\Company');
    }
}
