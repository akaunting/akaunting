<?php

namespace App\Models\Income;

use App\Models\Model;
use App\Traits\Currencies;

class InvoiceHistory extends Model
{

    use Currencies;

    protected $table = 'invoice_histories';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'invoice_id', 'status_code', 'notify', 'description'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Income\Invoice');
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
        return $this->belongsTo('App\Models\Income\InvoiceStatus', 'status_code', 'code');
    }

    public function getConvertedAmount($format = false)
    {
        return $this->convert($this->amount, $this->currency_code, $this->currency_rate, $format);
    }
}
