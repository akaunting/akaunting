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
}
