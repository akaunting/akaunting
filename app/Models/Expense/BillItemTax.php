<?php

namespace App\Models\Expense;

use App\Models\Model;
use App\Traits\Currencies;

class BillItemTax extends Model
{

    use Currencies;

    protected $table = 'bill_item_taxes';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_id', 'bill_item_id', 'tax_id', 'name', 'amount'];

    public function bill()
    {
        return $this->belongsTo('App\Models\Expense\Bill');
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
