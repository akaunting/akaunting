<?php

namespace App\Models\Common;

use App\Abstracts\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'alias', 'class', 'name', 'subject', 'body', 'params'];

    /**
     * Scope to only include contacts of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlias($query, $alias)
    {
        return $query->where('alias', $alias);
    }
}
