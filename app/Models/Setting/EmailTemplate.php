<?php

namespace App\Models\Setting;

use App\Abstracts\Model;
use Illuminate\Support\Str;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['title'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'alias', 'class', 'name', 'subject', 'body', 'params', 'created_from', 'created_by'];

    public function getTitleAttribute()
    {
        return trans($this->name);
    }

    public function getGroupAttribute()
    {
        if (Str::startsWith($this->alias, 'invoice_')) {
            $group = 'general.invoices';
        } elseif (Str::startsWith($this->alias, 'bill_')) {
            $group = 'general.bills';
        } elseif (Str::startsWith($this->alias, 'payment_')) {
            $group = 'general.payments';
        } else {
            $group = 'general.others';
        }

        return $group;
    }

    /**
     * Scope to only include email templates of a given alias.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAlias($query, $alias)
    {
        return $query->where('alias', $alias);
    }

    /**
     * Scope to only include email templates of a given module alias (class).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $alias
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModuleAlias($query, $alias)
    {
        $class = ($alias == 'core') ? 'App\\\\' : 'Modules\\\\' . Str::studly($alias) . '\\\\';

        return $query->where('class', 'like', $class . '%');
    }
}
