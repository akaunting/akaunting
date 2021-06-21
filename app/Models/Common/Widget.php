<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Widget extends Model
{
    use Cloneable, HasFactory;

    protected $table = 'widgets';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'dashboard_id', 'class', 'name', 'sort', 'settings', 'created_by'];

    /**
     * The attributes that should be cast.
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

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Widget::new();
    }
}
