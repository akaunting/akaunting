<?php

namespace App\Models\Sale;

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
    protected $fillable = ['company_id', 'invoice_id', 'status', 'notify', 'description'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Sale\Invoice');
    }
}
