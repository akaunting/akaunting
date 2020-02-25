<?php

namespace App\Models\Purchase;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Znck\Eloquent\Traits\BelongsToThrough;

class BillItemTax extends Model
{
    use Currencies, BelongsToThrough;

    protected $table = 'bill_item_taxes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_id', 'bill_item_id', 'tax_id', 'name', 'amount'];

    public function bill()
    {
        return $this->belongsTo('App\Models\Purchase\Bill');
    }

    public function item()
    {
        return $this->belongsToThrough('App\Models\Common\Item', 'App\Models\Purchase\BillItem', 'bill_item_id')->withDefault(['name' => trans('general.na')]);
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
