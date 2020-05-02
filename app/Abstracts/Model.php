<?php

namespace App\Abstracts;

use App\Scopes\Company;
use App\Traits\Tenants;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

abstract class Model extends Eloquent
{
    use Cachable, SearchString, SoftDeletes, Sortable, Tenants;

    protected $tenantable = true;

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

        $search = $request->get('search');
        $limit = $request->get('limit', setting('default.list_limit', '25'));

        return $query->usingSearchString($search)->sortable($sort)->paginate($limit);
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
            return $query;
        }

        return $query->whereIn('account_id', (array) $accounts);
    }

    public function scopeContact($query, $contacts)
    {
        if (empty($contacts)) {
            return $query;
        }

        return $query->whereIn('contact_id', (array) $contacts);
    }
}
