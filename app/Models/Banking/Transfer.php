<?php

namespace App\Models\Banking;

use App\Models\Model;
use App\Traits\Currencies;

class Transfer extends Model
{
    use Currencies;

    protected $table = 'transfers';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'payment_id', 'revenue_id'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['payment.paid_at', 'payment.amount', 'payment.name', 'revenue.name'];

    public function payment()
    {
        return $this->belongsTo('App\Models\Expense\Payment');
    }

    public function paymentAccount()
    {
        return $this->belongsTo('App\Models\Banking\Account', 'payment.account_id', 'id');
    }

    public function revenue()
    {
        return $this->belongsTo('App\Models\Income\Revenue');
    }

    public function revenueAccount()
    {
        return $this->belongsTo('App\Models\Banking\Account', 'revenue.account_id', 'id');
    }

    public function getDynamicConvertedAmount($format = false)
    {
        return $this->dynamicConvert($this->default_currency_code, $this->amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function getReverseConvertedAmount($format = false)
    {
        return $this->reverseConvert($this->amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function getDivideConvertedAmount($format = false)
    {
        return $this->divide($this->amount, $this->currency_code, $this->currency_rate, $format);
    }
}
