<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Report extends Model
{
    use Cloneable;

    protected $table = 'reports';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'class', 'name', 'description', 'settings', 'created_from', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'settings'      => 'object',
        'deleted_at'    => 'datetime',
    ];

    /**
     * Scope to only include reports of a given alias.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlias($query, $alias)
    {
        $class = ($alias == 'core') ? 'App\\\\' : 'Modules\\\\' . Str::studly($alias) . '\\\\';

        return $query->where('class', 'like', $class . '%');
    }

    /**
     * Scope to only include reports of a given class.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $class
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClass($query, $class)
    {
        return $query->where('class', '=', $class);
    }

    public function scopeExpenseSummary(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\ExpenseSummary');
    }

    public function scopeIncomeSummary(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\IncomeSummary');
    }

    public function scopeIncomeExpenseSummary(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\IncomeExpenseSummary');
    }

    public function scopeProfitLoss(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\ProfitLoss');
    }

    public function scopeTaxSummary(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('class'), '=', 'App\\Reports\\TaxSummary');
    }

    /**
     * Get the alias based on class.
     *
     * @return string
     */
    public function getAliasAttribute()
    {
        if (Str::startsWith($this->class, 'App\\')) {
            return 'core';
        }

        $arr = explode('\\', $this->class);

        return Str::kebab($arr[1]);
    }
}
