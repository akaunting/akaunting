<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;

class Dashboard extends Model
{
    use Cloneable;

    protected $table = 'dashboards';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'user_id', 'name', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'enabled'];

    public function widgets()
    {
        return $this->hasMany('App\Models\Common\DashboardWidget')->orderBy('sort', 'asc');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }

    public function scopeGetByUser($query, $user_id)
    {
        return $query->where('user_id', $user_id)->get();
    }
}
