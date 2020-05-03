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
        return $this->hasMany('App\Models\Purchase\Bill');
    }

    public function expense_transactions()
    {
        return $this->transactions()->where('type', 'expense');
    }

    public function income_transactions()
    {
        return $this->transactions()->where('type', 'income');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Sale\Invoice');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Common\Item');
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

        return $query->whereIn($this->table . '.type', (array) $types);
    }

    /**
     * Scope to include only income.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIncome($query)
    {
        return $query->where($this->table . '.type', '=', 'income');
    }

    /**
     * Scope to include only expense.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpense($query)
    {
        return $query->where($this->table . '.type', '=', 'expense');
    }

    /**
     * Scope to include only item.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeItem($query)
    {
        return $query->where($this->table . '.type', '=', 'item');
    }

    /**
     * Scope to include only other.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOther($query)
    {
        return $query->where($this->table . '.type', '=', 'other');
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
    }

    /**
     * Scope transfer category.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTransfer($query)
    {
        return $query->where($this->table . '.type', '=', 'other')->pluck('id')->first();
    }
}
