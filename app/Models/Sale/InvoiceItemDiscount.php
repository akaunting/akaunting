<?php

namespace App\Models\Sale;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Znck\Eloquent\Traits\BelongsToThrough;

class InvoiceItemDiscount extends Model
{
    use Currencies, BelongsToThrough;

    protected $table = 'invoice_item_discounts';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'invoice_id', 'invoice_item_id', 'rate', 'type', 'name', 'amount'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Sale\Invoice');
    }

    public function item()
    {
        return $this->belongsToThrough('App\Models\Common\Item', 'App\Models\Sale\InvoiceItem', 'invoice_item_id')->withDefault(['name' => trans('general.na')]);
    }

    /**
     * Convert rate to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setRateAttribute($value)
    {
        $this->attributes['rate'] = (double) $value;
    }
}
