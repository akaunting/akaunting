<?php

namespace App\Models\Expense;

use App\Models\Model;
use App\Models\Setting\Tax;
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

    /**
     * Get the formatted name.
     *
     * @return string
     */
    public function getNameAttribute($value)
    {
        $name = $value;

        $percent = 0;

        // Discount
        if ($this->code == 'discount') {
            $name = trans($name);
            $percent = $this->bill->discount;
        }

        // Tax
        if ($this->code == 'tax') {
            $rate = Tax::where('name', $name)->value('rate');

            if (!empty($rate)) {
                $percent = $rate;
            }
        }

        if (!empty($percent)) {
            $name .= ' (';

            if (setting('general.percent_position', 'after') == 'after') {
                $name .= $percent . '%';
            } else {
                $name .= '%' . $percent;
            }

            $name .= ')';
        }

        return $name;
    }
}
