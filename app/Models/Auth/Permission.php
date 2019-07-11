<?php

namespace App\Models\Auth;

use EloquentFilter\Filterable;
use Laratrust\LaratrustPermission;
use Laratrust\Traits\LaratrustPermissionTrait;
use Kyslik\ColumnSortable\Sortable;
use Request;
use Route;

class Permission extends LaratrustPermission
{
    use LaratrustPermissionTrait;
    use Filterable;
    use Sortable;


    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'description'];

    /**
     * Define the filter provider globally.
     *
     * @return ModelFilter
     */
    public function modelFilter()
    {
        // Check if is api or web
        if (Request::is('api/*')) {
            $arr = array_reverse(explode('\\', explode('@', app()['api.router']->currentRouteAction())[0]));
            $folder = $arr[1];
            $file = $arr[0];
        } else {
            list($folder, $file) = explode('/', Route::current()->uri());
        }

        if (empty($folder) || empty($file)) {
            return $this->provideFilter();
        }

        $class = '\App\Filters\\' . ucfirst($folder) .'\\' . ucfirst($file);

        return $this->provideFilter($class);
    }

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

        $input = $request->input();
        $limit = $request->get('limit', setting('general.list_limit', '25'));

        return $query->filter($input)->sortable($sort)->paginate($limit);
    }
}
