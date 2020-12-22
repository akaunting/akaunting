<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;

class Widget extends Model
{
    use Cloneable;

    protected $table = 'widgets';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'dashboard_id', 'class', 'name', 'sort', 'settings'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'object',
    ];

    public function dashboard()
    {
        return $this->belongsTo('App\Models\Common\Dashboard');
    }

    public function users()
    {
        return $this->hasManyThrough('App\Models\Auth\User', 'App\Models\Common\Dashboard');
    }
}
