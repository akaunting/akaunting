<?php

namespace App\Models\Setting;

use App\Scopes\Company;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Setting extends Eloquent
{
    protected $table = 'settings';

    public $timestamps = false;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'key', 'value'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new Company);
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
