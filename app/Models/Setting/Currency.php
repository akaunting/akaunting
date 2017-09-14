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
        return $this->hasMany('App\Models\Banking\Account');
    }

    public function customers()
    {
        return $this->hasMany('App\Models\Income\Customer');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Income\Invoice', 'code', 'currency_code');
    }

    public function revenues()
    {
        return $this->hasMany('App\Models\Income\Revenue', 'code', 'currency_code');
    }

    public function bills()
    {
        return $this->hasMany('App\Models\Expense\Bill', 'code', 'currency_code');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Expense\Payment', 'code', 'currency_code');
    }
}
