<?php

namespace App\Abstracts;

use Akaunting\Sortable\Traits\Sortable;
use App\Events\Common\SearchStringApplied;
use App\Events\Common\SearchStringApplying;
use App\Interfaces\Export\WithParentSheet;
use App\Traits\DateTime;
use App\Traits\Owners;
use App\Traits\Sources;
use App\Traits\Tenants;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laratrust\Contracts\Ownable;
use Lorisleiva\LaravelSearchString\Concerns\SearchString;

abstract class Model extends Eloquent implements Ownable
{
    use Cachable, DateTime, Owners, SearchString, SoftDeletes, Sortable, Sources, Tenants;

    protected $tenantable = true;

    protected $casts = [
        'amount'        => 'double',
        'enabled'       => 'boolean',
        'deleted_at'    => 'datetime',
    ];

    public $allAttributes = [];

    /**
     * Fill the model with an array of attributes.
     *
     * @param  array  $attributes
     * @return $this
     *
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function fill(array $attributes)
    {
        $this->allAttributes = $attributes;

        return parent::fill($attributes);
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
        return $this->belongsTo(user_model_class(), 'created_by', 'id')->withDefault(['name' => trans('general.na')]);
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
        return $query->where($this->qualifyColumn('company_id'), '=', $company_id);
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

        /**
         * Modules that use the sort parameter in CRUD operations cause an error,
         * so this sort parameter set back to old value after the query is executed.
         *
         * for Custom Fields module
         */
        $request_sort = $request->get('sort');

        $query->usingSearchString()->sortable($sort);

        if ($request->expectsJson() && $request->isNotApi()) {
            return $query->get();
        }

        $request->merge(['sort' => $request_sort]);
        // This line disabled because broken sortable issue.
        //$request->offsetUnset('direction');
        $limit = (int) $request->get('limit', setting('default.list_limit', '25'));

        return $query->paginate($limit);
    }

    public function scopeUsingSearchString(Builder $query, string|null $string = null)
    {
        event(new SearchStringApplying($query));

        $string = $string ?: request('search');

        $this->getSearchStringManager()->updateBuilder($query, $string);

        event(new SearchStringApplied($query));
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

        if (! $this instanceof WithParentSheet && (empty($ids) || count((array) $ids) > $limit)) {
            $query->offset($offset)->limit($limit);
        }

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
        return $query->where($this->qualifyColumn('enabled'), 1);
    }

    /**
     * Scope to only include passive models.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisabled($query)
    {
        return $query->where($this->qualifyColumn('enabled'), 0);
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
        return $query->where($this->qualifyColumn('reconciled'), $value);
    }

    public function scopeAccount($query, $accounts)
    {
        if (empty($accounts)) {
            return $query;
        }

        return $query->whereIn($this->qualifyColumn('account_id'), (array) $accounts);
    }

    public function scopeContact($query, $contacts)
    {
        if (empty($contacts)) {
            return $query;
        }

        return $query->whereIn($this->qualifyColumn('contact_id'), (array) $contacts);
    }

    public function scopeSource($query, $source)
    {
        return $query->where($this->qualifyColumn('created_from'), $source);
    }

    public function scopeIsOwner($query)
    {
        return $query->where($this->qualifyColumn('created_by'), user_id());
    }

    public function scopeIsNotOwner($query)
    {
        return $query->where($this->qualifyColumn('created_by'), '<>', user_id());
    }

    public function scopeIsRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'like', '%-recurring');
    }

    public function scopeIsNotRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'not like', '%-recurring');
    }

    public function scopeModuleEnabled(Builder $query, string $module): Builder
    {
        return $query->allCompanies()->whereHas('company', fn (Builder $q1) =>
            $q1->enabled()->whereHas('modules', fn (Builder $q2) =>
                $q2->allCompanies()->alias($module)->enabled(),
            )
        );
    }

    public function ownerKey($owner)
    {
        if ($this->isNotOwnable()) {
            return 0;
        }

        return $this->created_by;
    }
}
