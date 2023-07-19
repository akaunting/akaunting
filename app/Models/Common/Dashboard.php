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
    protected $fillable = ['company_id', 'name', 'enabled', 'created_from', 'created_by'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'enabled'];

    public function users()
    {
        return $this->belongsToMany('App\Models\Auth\User', 'App\Models\Auth\UserDashboard');
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
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        if ($this->enabled) {
            $actions[] = [
                'title' => trans('general.switch'),
                'icon' => 'settings_ethernet',
                'url' => route('dashboards.switch', $this->id),
                'permission' => 'read-common-dashboards',
                'attributes' => [
                    'id' => 'index-line-actions-switch-dashboard-' . $this->id,
                ],
            ];
        }

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('dashboards.edit', $this->id),
            'permission' => 'update-common-dashboards',
            'attributes' => [
                'id' => 'index-line-actions-edit-dashboard-' . $this->id,
            ],
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'dashboards.destroy',
            'permission' => 'delete-common-dashboards',
            'attributes' => [
                'id' => 'index-line-actions-delete-dashboard-' . $this->id,
            ],
            'model' => $this,
        ];

        return $actions;
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
