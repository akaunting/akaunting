<?php

namespace App\Models\Expense;

use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillPayment extends Model
{

    use Cloneable, Currencies, SoftDeletes;

    protected $table = 'bill_payments';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_id', 'account_id', 'paid_at', 'amount', 'currency_code', 'currency_rate', 'description', 'payment_method', 'reference', 'reconciled'];

    public function bill()
    {
        return $this->belongsTo('App\Models\Expense\Bill');
    }

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account');
    }

    /**
     * Order by paid date.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
    }

    /**
     * Scope paid invoice.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaid($query)
    {
        return $query->sum('amount');
    }

    /**
     * Convert amount to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }

    /**
     * Convert currency rate to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (double) $value;
    }

    public function getDivideConvertedAmount($format = false)
    {
        return $this->divide($this->amount, $this->currency_code, $this->currency_rate, $format);
    }
}
