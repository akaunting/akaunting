<?php

namespace App\Models\Common;

use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Support\Str;

class Report extends Model
{
    use Cloneable;

    protected $table = 'reports';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'class', 'name', 'description', 'settings', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'object',
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
