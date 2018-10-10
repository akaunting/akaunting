<?php

namespace App\Models\Expense;

use App\Models\Model;
use Bkwld\Cloner\Cloneable;
use App\Traits\Currencies;
use Sofa\Eloquence\Eloquence;
use App\Traits\Media;

class Vendor extends Model
{
    use Cloneable, Currencies, Eloquence, Media;

    protected $table = 'vendors';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'email', 'tax_number', 'phone', 'address', 'website', 'currency_code', 'reference', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'email', 'phone', 'enabled'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name'    => 10,
        'email'   => 5,
        'phone'   => 2,
        'website' => 2,
        'address' => 1,
    ];

    public function bills()
    {
        return $this->hasMany('App\Models\Expense\Bill');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Expense\Payment');
    }

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getLogoAttribute($value)
    {
        if (!empty($value) && !$this->hasMedia('logo')) {
            return $value;
        } elseif (!$this->hasMedia('logo')) {
            return false;
        }

        return $this->getMedia('logo')->last();
    }

    public function getUnpaidAttribute()
    {
        $amount = 0;

        $bills = $this->bills()->accrued()->notPaid()->get();

        foreach ($bills as $bill) {
            $bill_amount = $bill->amount - $bill->paid;

            $amount += $this->dynamicConvert(setting('general.default_currency'), $bill_amount, $bill->currency_code, $bill->currency_rate, false);
        }

        return $amount;
    }
}
