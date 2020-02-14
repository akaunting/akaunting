<?php

namespace App\Models\Setting;

use App\Abstracts\Model;

class Currency extends Model
{

    protected $table = 'currencies';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'code', 'rate', 'enabled', 'precision', 'symbol', 'symbol_first', 'decimal_mark', 'thousands_separator'];

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

    public function bills()
    {
        return $this->hasMany('App\Models\Purchase\Bill', 'currency_code', 'code');
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Common\Contact', 'currency_code', 'code');
    }

    public function customers()
    {
        return $this->contacts()->where('type', 'customer');
    }

    public function expense_transactions()
    {
        return $this->transactions()->where('type', 'expense');
    }

    public function income_transactions()
    {
        return $this->transactions()->where('type', 'income');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Sale\Invoice', 'currency_code', 'code');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction', 'currency_code', 'code');
    }

    public function vendors()
    {
        return $this->contacts()->where('type', 'vendor');
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
     * Get the current precision.
     *
     * @return string
     */
    public function getPrecisionAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.precision');
        }

        return (int) $value;
    }

    /**
     * Get the current symbol.
     *
     * @return string
     */
    public function getSymbolAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.symbol');
        }

        return $value;
    }

    /**
     * Get the current symbol_first.
     *
     * @return string
     */
    public function getSymbolFirstAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.symbol_first');
        }

        return $value;
    }

    /**
     * Get the current decimal_mark.
     *
     * @return string
     */
    public function getDecimalMarkAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.decimal_mark');
        }

        return $value;
    }

    /**
     * Get the current thousands_separator.
     *
     * @return string
     */
    public function getThousandsSeparatorAttribute($value)
    {
        if (is_null($value)) {
            return config('money.' . $this->code . '.thousands_separator');
        }

        return $value;
    }
}
