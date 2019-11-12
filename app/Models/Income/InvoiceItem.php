<?php

namespace App\Models\Income;

use App\Models\Model;
use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;

class InvoiceItem extends Model
{

    use Currencies, Cloneable;

    protected $table = 'invoice_items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'invoice_id', 'item_id', 'name', 'sku', 'quantity', 'price', 'total',  'tax'];
    
    public $cloneable_relations = ['itemTaxes'];

    public function invoice()
    {
        return $this->belongsTo('App\Models\Income\Invoice');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item');
    }

    public function taxes()
    {
        return $this->hasMany('App\Models\Income\InvoiceItemTax', 'invoice_item_id', 'id');
    }


    public function tax()
    {
        return $this->belongsTo('App\Models\Setting\Tax');
    }

    public function taxType()
    {
        return $this->belongsTo('App\Models\Setting\Tax' , 'tax_id');
    }

    /**
     * Convert price to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (double) $value;
    }

    /**
     * Convert total to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = (double) $value;
    }

    /**
     * Convert tax to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setTaxAttribute($value)
    {
        $this->attributes['tax'] = (double) $value;
    }
}
