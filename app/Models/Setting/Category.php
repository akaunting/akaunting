<?php

namespace App\Models\Setting;

use App\Abstracts\Model;

class Category extends Model
{
    protected $table = 'categories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'type', 'color', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'type', 'enabled'];

    public function bills()
    {
        return $this->hasMany('App\Models\Expense\Bill');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Income\Invoice');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Common\Item');
    }

    public function payments()
    {
        return $this->transactions()->where('type', 'expense');
    }

    public function revenues()
    {
        return $this->transactions()->where('type', 'income');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }

    /**
     * Scope to only include categories of a given type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $types
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, $types)
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn('type', (array) $types);
    }

    /**
     * Scope transfer category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTransfer($query)
    {
        return $query->where('type', 'other')->pluck('id')->first();
    }
}
