<?php

namespace App\Models\Expense;

use App\Models\Model;
use App\Traits\Currencies;

class BillHistory extends Model
{

    use Currencies;

    protected $table = 'bill_histories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_id', 'status_code', 'notify', 'description'];

    public function bill()
    {
        return $this->belongsTo('App\Models\Expense\Bill');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item');
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax');
    }

    public function payment()
    {
        return $this->belongsTo('App\Models\Setting\Payment');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Expense\BillStatus', 'status_code', 'code');
    }

    public function getConvertedAmount($format = false)
    {
        return $this->convert($this->amount, $this->currency_code, $this->currency_rate, $format);
    }
}
