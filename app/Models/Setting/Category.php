<?php

namespace App\Models\Setting;

use App\Abstracts\Model;
use App\Models\Document\Document;
use App\Traits\Transactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, Transactions;

    protected $table = 'categories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'type', 'color', 'enabled', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'type', 'enabled'];

    public function documents()
    {
        return $this->hasMany('App\Models\Document\Document');
    }

    public function bills()
    {
        return $this->documents()->where('type', Document::BILL_TYPE);
    }

    public function expense_transactions()
    {
        return $this->transactions()->whereIn('type', (array) $this->getExpenseTypes());
    }

    public function income_transactions()
    {
        return $this->transactions()->whereIn('type', (array) $this->getIncomeTypes());
    }

    public function invoices()
    {
        return $this->documents()->where('type', Document::INVOICE_TYPE);
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
        return (int) $query->other()->pluck('id')->first();
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Category::new();
    }
}
