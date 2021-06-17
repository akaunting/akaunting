<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Dashboard extends Model
{
    use Cloneable, HasFactory;

    protected $table = 'dashboards';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'enabled', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

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

    /**
     * Scope to only include dashboards of a given user id.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $user_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUserId($query, $user_id)
    {
        return $query->whereHas('users', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        });
    }

    /**
     * Scope to only include dashboards of a given alias.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlias($query, $alias)
    {
        $class = ($alias == 'core') ? 'App\\\\' : 'Modules\\\\' . Str::studly($alias) . '\\\\';

        return $query->whereHas('widgets', function ($query) use ($class) {
                    // Must have widgets of module
                    $query->where('class', 'like', $class . '%');
                })->whereDoesntHave('widgets', function ($query) use ($class) {
                    // Must not have widgets from other modules
                    $query->where('class', 'not like', $class . '%');
                });
    }

    /**
     * Get the alias based on class.
     *
     * @return string
     */
    public function getAliasAttribute()
    {
        $alias = '';

        foreach ($this->widgets as $widget) {
            if (Str::startsWith($widget->class, 'App\\')) {
                $tmp_alias = 'core';
            } else {
                $arr = explode('\\', $widget->class);

                $tmp_alias = Str::kebab($arr[1]);
            }

            // First time set
            if ($alias == '') {
                $alias = $tmp_alias;
            }

            // Must not have widgets from different modules
            if ($alias != $tmp_alias) {
                $alias = '';

                break;
            }
        }

        return $alias;
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Dashboard::new();
    }
}
