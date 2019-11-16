<?php

namespace App\Models\Common;

use App\Abstracts\Model;

class DashboardWidget extends Model
{

    protected $table = 'dashboard_widgets';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'user_id', 'dashboard_id', 'widget_id', 'name', 'settings', 'sort'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\Auth\User', 'user_id', 'id');
    }

    public function dashboard()
    {
        return $this->belongsTo('App\Models\Common\Dashboard');
    }

    public function widget()
    {
        return $this->belongsTo('App\Models\Common\Widget');
    }

    public function getNameAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return $this->widget->name;
    }

    public function getSettingsAttribute($value)
    {
        if (!empty($value)) {
            $value = json_decode($value, true);

            $value['widget'] = $this;
        } else {
            $value = [
                'widget' => $this
            ];
        }

        return $value;
    }
}
