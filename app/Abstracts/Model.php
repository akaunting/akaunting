<?php

namespace App\Abstracts;

use App\Traits\DateTime;
use App\Traits\Owners;
use App\Traits\Tenants;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Laratrust\Contracts\Ownable;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

abstract class Model extends Eloquent implements Ownable
{
    use Cachable, DateTime, Owners, SearchString, SoftDeletes, Sortable, Tenants;

    protected $tenantable = true;

    protected $dates = ['deleted_at'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public $allAttributes = [];

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->allAttributes = $attributes;

        parent::__construct($attributes);
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @return bool
     */
    public function update(array $attributes = [], array $options = [])
    {
        $this->allAttributes = $attributes;

        return parent::update($attributes, $options);
    }

    public static function observe($classes)
    {
        parent::observe($classes);
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
     * Owner relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\Auth\User', 'created_by', 'id')->withDefault(['name' => trans('general.na')]);
    }

    /**
     * Scope to only include company data.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllCompanies($query)
    {
        return $query->withoutGlobalScope('App\Scopes\Company');
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

        $query->usingSearchString($search)->sortable($sort);

        if ($request->expectsJson() && $request->isNotApi()) {
            return $query->get();
        }

        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));

        return $query->paginate($limit);
    }

    /**
     * Scope to export the rows of the current page filtered and sorted.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $ids
     * @param $sort
     * @param $id_field
     *
     * @return \Illuminate\Support\LazyCollection
     */
    public function scopeCollectForExport($query, $ids = [], $sort = 'name', $id_field = 'id')
    {
        $request = request();

        if (!empty($ids)) {
            $query->whereIn($id_field, (array) $ids);
        }

        $search = $request->get('search');

        $query->usingSearchString($search)->sortable($sort);

        $page = (int) $request->get('page');
        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));
        $offset = $page ? ($page - 1) * $limit : 0;

        $query->offset($offset)->limit($limit);

        return $query->cursor();
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

    public function scopeIsOwner($query)
    {
        return $query->where('created_by', user_id());
    }

    public function scopeIsNotOwner($query)
    {
        return $query->where('created_by', '<>', user_id());
    }

    public function ownerKey($owner)
    {
        if ($this->isNotOwnable()) {
            return 0;
        }

        return $this->created_by;
    }
}
