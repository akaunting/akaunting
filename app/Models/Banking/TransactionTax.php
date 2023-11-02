<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Models\Banking\Transaction;
use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Builder;

class TransactionTax extends Model
{
    use Cloneable, Currencies;

    protected $table = 'transaction_taxes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'type', 'transaction_id', 'tax_id', 'name', 'amount', 'created_from', 'created_by'];

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax')->withDefault(['name' => trans('general.na'), 'rate' => 0]);
    }

    public function transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction')->withDefault(['name' => trans('general.na')]);
    }

    public function scopeType(Builder $query, string $type)
    {
        return $query->where($this->qualifyColumn('type'), '=', $type);
    }

    public function scopeIncome(Builder $query)
    {
        return $query->where($this->qualifyColumn('type'), '=', Transaction::INCOME_TYPE);
    }

    public function scopeIncomeRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', Transaction::INCOME_RECURRING_TYPE)
                    ->whereHas('document.recurring', function (Builder $query) {
                        $query->whereNull('deleted_at');
                    });
    }

    public function scopeExpense(Builder $query)
    {
        return $query->where($this->qualifyColumn('type'), '=', Transaction::EXPENSE_TYPE);
    }

    public function scopeExpenseRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', Transaction::EXPENSE_RECURRING_TYPE)
                    ->whereHas('document.recurring', function (Builder $query) {
                        $query->whereNull('deleted_at');
                    });
    }
}
