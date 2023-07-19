<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Widget extends Model
{
    use Cloneable, HasFactory;

    protected $table = 'widgets';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'dashboard_id', 'class', 'name', 'sort', 'settings', 'created_from', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'settings'      => 'object',
        'deleted_at'    => 'datetime',
    ];

    /**
     * Scope to only include widgets of a given alias.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlias($query, $alias)
    {
        $class = ($alias == 'core') ? 'App\\\\' : 'Modules\\\\' . Str::studly($alias) . '\\\\';

        return $query->where('class', 'like', $class . '%');
    }

    public function dashboard()
    {
        return $this->belongsTo('App\Models\Common\Dashboard');
    }

    public function users()
    {
        return $this->hasManyThrough('App\Models\Auth\User', 'App\Models\Common\Dashboard');
    }

    /**
     * Get the alias based on class.
     *
     * @return string
     */
    public function getAliasAttribute()
    {
        if (Str::startsWith($this->class, 'App\\')) {
            return 'core';
        }

        $arr = explode('\\', $this->class);

        return Str::kebab($arr[1]);
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
