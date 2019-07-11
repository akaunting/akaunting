<?php

namespace App\Models\Income;

use App\Models\Model;
use App\Traits\Currencies;

class InvoiceItemTax extends Model
{

    use Currencies;

    protected $table = 'invoice_item_taxes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'invoice_id', 'invoice_item_id', 'tax_id', 'name', 'amount'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Income\Invoice');
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax');
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
}
