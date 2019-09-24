<?php

namespace App\Models\Setting;

use App\Models\Model;

class Tax extends Model
{

    protected $table = 'taxes';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['title'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'rate', 'type', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'rate', 'enabled'];

    public function items()
    {
        return $this->hasMany('App\Models\Common\Item');
    }

    public function bill_items()
    {
        return $this->hasMany('App\Models\Expense\BillItemTax');
    }

    public function invoice_items()
    {
        return $this->hasMany('App\Models\Income\InvoiceItemTax');
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

    /**
     * Get the name including rate.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        $title = $this->name . ' (';

        if (setting('general.percent_position', 'after') == 'after') {
            $title .= $this->rate . '%';
        } else {
            $title .= '%' . $this->rate;
        }

        $title .= ')';

        return $title;
    }
}
