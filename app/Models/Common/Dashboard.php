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
    protected $fillable = ['company_id', 'name', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'enabled'];

    public function users()
    {
        return $this->morphedByMany('App\Models\Auth\User', 'user', 'user_dashboards', 'dashboard_id', 'user_id');
    }

    public function widgets()
    {
        return $this->hasMany('App\Models\Common\Widget')->orderBy('sort', 'asc');
    }
}
