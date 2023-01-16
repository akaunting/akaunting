<?php

namespace App\Models\Setting;

use App\Abstracts\Model;

class Setting extends Model
{
    protected $table = 'settings';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'key', 'value'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
}
