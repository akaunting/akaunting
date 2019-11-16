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
    protected $fillable = ['company_id', 'name', 'alias', 'settings', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'alias', 'enabled'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];

    public function dashboard_widgets()
    {
        return $this->hasMany('App\Models\Common\DashboardWidget');
    }

    public function dashboard_widget()
    {
        return $this->belongsTo('App\Models\Common\DashboardWidget', 'id', 'widget_id')->where('dashboard_id', 1);
    }
}
