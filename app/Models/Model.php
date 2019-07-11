<?php

namespace App\Models;

use App\Scopes\Company;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Request;
use Route;

class Model extends Eloquent
{
    use Filterable, SoftDeletes, Sortable;

    protected $dates = ['deleted_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new Company);
    }

    /**
     * Global company relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Common\Company');
    }

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

        $class = '\App\Filters\\' . ucfirst($folder) . '\\' . ucfirst($file);

        return $this->provideFilter($class);
    }

    /**
     * Scope to only include company data.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $company_id
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompanyId($query, $company_id)
    {
        return $query->where($this->table . '.company_id', '=', $company_id);
    }

    /**
     * Scope to get all rows filtered, sorted and paginated.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $sort
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCollect($query, $sort = 'name')
    {
        $request = request();

        $input = $request->input();
        $limit = $request->get('limit', setting('general.list_limit', '25'));

        return $query->filter($input)->sortable($sort)->paginate($limit);
    }

    /**
     * Scope to only include active models.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('enabled', 1);
    }

    /**
     * Scope to only include passive models.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisabled($query)
    {
        return $query->where('enabled', 0);
    }

    /**
     * Scope to only include reconciled models.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReconciled($query, $value = 1)
    {
        return $query->where('reconciled', $value);
    }

    public function scopeAccount($query, $accounts)
    {
        if (empty($accounts)) {
            return;
        }

        return $query->whereIn('account_id', (array) $accounts);
    }

    public function scopeCustomer($query, $customers)
    {
        if (empty($customers)) {
            return;
        }

        return $query->whereIn('customer_id', (array) $customers);
    }

    public function scopeVendor($query, $vendors)
    {
        if (empty($vendors)) {
            return;
        }

        return $query->whereIn('vendor_id', (array) $vendors);
    }
}
