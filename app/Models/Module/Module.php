<?php

namespace App\Models\Module;

use App\Abstracts\Model;

class Module extends Model
{
    protected $table = 'modules';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'alias', 'enabled', 'created_from', 'created_by'];

    /**
     * Scope alias.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlias($query, $alias)
    {
        return $query->where('alias', $alias);
    }
}
