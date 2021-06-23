<?php

namespace App\Models\Auth;

use App\Traits\Tenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Models\LaratrustRole;
use Laratrust\Traits\LaratrustRoleTrait;
use Kyslik\ColumnSortable\Sortable;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

class Role extends LaratrustRole
{
    use HasFactory, LaratrustRoleTrait, SearchString, Sortable, Tenants;

    protected $table = 'roles';

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
        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));

        return $query->usingSearchString($search)->sortable($sort)->paginate($limit);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Role::new();
    }
}
