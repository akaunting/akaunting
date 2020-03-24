<?php

namespace App\Models\Sale;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Bkwld\Cloner\Cloneable;

class InvoiceItem extends Model
{
    use Cloneable, Currencies;

    protected $table = 'invoice_items';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'invoice_id',
        'item_id',
        'name',
        'quantity',
        'price',
        'total',
        'tax',
        'discount_rate',
        'discount_type',
    ];

    /**
     * Clonable relationships.
     *
     * @var array
     */
    public $cloneable_relations = ['taxes'];

    public static function boot()
    {
        parent::boot();

        static::retrieved(function($model) {
            $model->setTaxIds();
        });
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Sale\Invoice');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Common\Item')->withDefault(['name' => trans('general.na')]);
    }

    public function taxes()
    {
        return $this->hasMany('App\Models\Sale\InvoiceItemTax', 'invoice_item_id', 'id');
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

    /**
     * Get the formatted discount.
     *
     * @return string
     */
    public function getDiscountRateAttribute($value)
    {
        if (setting('localisation.percent_position', 'after') === 'after') {
            $text = ($this->discount_type === 'normal') ? $value . '%' : $value;
        } else {
            $text = ($this->discount_type === 'normal') ? '%' . $value : $value;
        }

        return $text;
    }

    /**
     * Convert tax to Array.
     *
     * @return void
     */
    public function setTaxIds()
    {
        $tax_ids = [];

        foreach ($this->taxes as $tax) {
            $tax_ids[] = (string) $tax->tax_id;
        }

        $this->setAttribute('tax_id', $tax_ids);
    }

    public function onCloning($src, $child = null)
    {
        unset($this->tax_id);
    }
}
