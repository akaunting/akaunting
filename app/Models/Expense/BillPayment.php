<?php

namespace App\Models\Expense;

use App\Models\Model;
use App\Traits\Currencies;
use App\Traits\DateTime;

class BillPayment extends Model
{
    use Currencies, DateTime;

    protected $table = 'bill_payments';

    protected $dates = ['deleted_at', 'paid_at'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_id', 'account_id', 'paid_at', 'amount', 'currency_code', 'currency_rate', 'description', 'payment_method', 'reference', 'attachment'];

    public function account()
    {
        return $this->belongsTo('App\Models\Banking\Account');
    }

    public function bill()
    {
        return $this->belongsTo('App\Models\Expense\Bill');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item\Item');
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax');
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
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
}
