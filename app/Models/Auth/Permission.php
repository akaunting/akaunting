<?php

namespace App\Models\Auth;

use Laratrust\Models\LaratrustPermission;
use Laratrust\Traits\LaratrustPermissionTrait;
use Kyslik\ColumnSortable\Sortable;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class Permission extends LaratrustPermission
{
    use LaratrustPermissionTrait, SearchString, Sortable;

    protected $table = 'permissions';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['title'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * Scope to get all rows filtered, sorted and paginated.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCollect($query, $sort = 'display_name')
    {
        $request = request();

        $search = $request->get('search');
        $limit = $request->get('limit', setting('default.list_limit', '25'));

        return $query->usingSearchString($search)->sortable($sort)->paginate($limit);
    }

    /**
     * Scope to only include by action.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $action
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAction($query, $action = 'read')
    {
        return $query->where('name', 'like', $action . '-%');
    }

    /**
     * Transform display name.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        $replaces = [
            'Create ' => '',
            'Read ' => '',
            'Update ' => '',
            'Delete ' => '',
            'Modules' => 'Apps',
        ];

        $title = str_replace(array_keys($replaces), array_values($replaces), $this->display_name);

        return $title;
    }
}
