<?php

namespace App\Models\Setting;

use App\Traits\Tenants;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Setting extends Eloquent
{
    use Tenants;

    protected $table = 'settings';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'key', 'value'];

    public $allAttributes = [];

    public $timestamps = false;

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

    public function company()
    {
        return $this->belongsTo('App\Models\Common\Company');
    }

    /**
     * Scope to only include by prefix.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $prefix
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrefix($query, $prefix = 'company')
    {
        return $query->where('key', 'like', $prefix . '.%');
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
}
