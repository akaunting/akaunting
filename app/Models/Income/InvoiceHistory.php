<?php

namespace App\Models\Income;

use App\Abstracts\Model;
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

    public function status()
    {
        return $this->belongsTo('App\Models\Income\InvoiceStatus', 'status_code', 'code');
    }
}
