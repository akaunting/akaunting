<?php

namespace App\Models\Expense;

use App\Models\Model;
use App\Traits\DateTime;

class BillTotal extends Model
{
    use DateTime;

    protected $table = 'bill_totals';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'bill_id', 'code', 'name', 'amount', 'sort_order'];

    public function bill()
    {
        return $this->belongsTo('App\Models\Expense\Bill');
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
