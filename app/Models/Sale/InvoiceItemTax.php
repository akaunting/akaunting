<?php

namespace App\Models\Sale;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Znck\Eloquent\Traits\BelongsToThrough;

class InvoiceItemTax extends Model
{
    use Currencies, BelongsToThrough;

    protected $table = 'invoice_item_taxes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'invoice_id', 'invoice_item_id', 'tax_id', 'name', 'amount'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Sale\Invoice');
    }

    public function item()
    {
        return $this->belongsToThrough('App\Models\Common\Item', 'App\Models\Sale\InvoiceItem', 'invoice_item_id')->withDefault(['name' => trans('general.na')]);
    }

    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax')->withDefault(['name' => trans('general.na'), 'rate' => 0]);
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
