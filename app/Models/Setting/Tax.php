<?php

namespace App\Models\Setting;

use App\Models\Model;

class Tax extends Model
{

    protected $table = 'taxes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'rate', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'rate', 'enabled'];

    public function items()
    {
        return $this->hasMany('App\Models\Item\Item');
    }

    public function bill_items()
    {
        return $this->hasMany('App\Models\Expense\BillItem');
    }

    public function invoice_items()
    {
        return $this->hasMany('App\Models\Income\InvoiceItem');
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
