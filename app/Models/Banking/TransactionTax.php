<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Models\Banking\Transaction;
use App\Traits\Currencies;
use App\Traits\Transactions;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Builder;

class TransactionTax extends Model
{
    use Cloneable, Currencies, Transactions;

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

    public function scopeType(Builder $query, $types): Builder
    {
        if (empty($types)) {
            return $query;
        }

        return $query->whereIn($this->qualifyColumn('type'), (array) $types);
    }

    public function scopeIncome(Builder $query)
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getIncomeTypes());
    }

    public function scopeIncomeTransfer(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', Transaction::INCOME_TRANSFER_TYPE);
    }

    public function scopeIncomeRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', Transaction::INCOME_RECURRING_TYPE)
                ->whereHas('transaction.recurring', function (Builder $query) {
                    $query->whereNull('deleted_at');
                });
    }

    public function scopeExpense(Builder $query)
    {
        return $query->whereIn($this->qualifyColumn('type'), (array) $this->getExpenseTypes());
    }

    public function scopeExpenseTransfer(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', Transaction::EXPENSE_TRANSFER_TYPE);
    }

    public function scopeExpenseRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), '=', Transaction::EXPENSE_RECURRING_TYPE)
                ->whereHas('transaction.recurring', function (Builder $query) {
                    $query->whereNull('deleted_at');
                });
    }

    public function scopeIsTransfer(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'like', '%-transfer');
    }

    public function scopeIsNotTransfer(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'not like', '%-transfer');
    }

    public function scopeIsRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'like', '%-recurring');
    }

    public function scopeIsNotRecurring(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'not like', '%-recurring');
    }

    public function scopeIsSplit(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'like', '%-split');
    }

    public function scopeIsNotSplit(Builder $query): Builder
    {
        return $query->where($this->qualifyColumn('type'), 'not like', '%-split');
    }

    public function scopeIsDocument(Builder $query): Builder
    {
        return $query->whereHas('transaction', function ($q) {
                    $q->whereNotNull('document_id');
                });
    }

    public function scopeIsNotDocument(Builder $query): Builder
    {
        return $query->whereHas('transaction', function ($q) {
                    $q->whereNull('document_id');
                });
    }
}
