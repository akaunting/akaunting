<?php

namespace App\Models\Setting;

use App\Models\Model;

class Currency extends Model
{

    protected $table = 'currencies';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'code', 'rate', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'code', 'rate', 'enabled'];

    public function accounts()
    {
        return $this->hasMany('App\Models\Banking\Account', 'currency_code', 'code');
    }

    public function customers()
    {
        return $this->hasMany('App\Models\Income\Customer', 'currency_code', 'code');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Income\Invoice', 'currency_code', 'code');
    }

    public function revenues()
    {
        return $this->hasMany('App\Models\Income\Revenue', 'currency_code', 'code');
    }

    public function bills()
    {
        return $this->hasMany('App\Models\Expense\Bill', 'currency_code', 'code');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Expense\Payment', 'currency_code', 'code');
    }

    public function canDisable()
    {
        $error = false;

        if ($this->code == setting('general.default_currency')) {
            $error['company'] = 1;
        }

        if ($accounts = $this->accounts()->count()) {
            $error['accounts'] = $accounts;
        }

        if ($customers = $this->customers()->count()) {
            $error['customers'] = $customers;
        }

        if ($invoices = $this->invoices()->count()) {
            $error['invoices'] = $invoices;
        }

        if ($revenues = $this->revenues()->count()) {
            $error['revenues'] = $revenues;
        }

        if ($bills = $this->bills()->count()) {
            $error['bills'] = $bills;
        }

        if ($payments = $this->payments()->count()) {
            $error['payments'] = $payments;
        }

        if ($error) {
            return $error;
        }

        return true;
    }
}
