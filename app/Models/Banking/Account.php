<?php

namespace App\Models\Banking;

use App\Abstracts\Model;

class Account extends Model
{
    protected $table = 'accounts';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['balance'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'name', 'number', 'currency_code', 'opening_balance', 'bank_name', 'bank_phone', 'bank_address', 'enabled'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'number', 'opening_balance', 'enabled'];

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function revenues()
    {
        return $this->transactions()->where('type', 'income');
    }

    public function payments()
    {
        return $this->transactions()->where('type', 'expense');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }

    /**
     * Convert opening balance to double.
     *
     * @param  string  $value
     * @return void
     */
    public function setOpeningBalanceAttribute($value)
    {
        $this->attributes['opening_balance'] = (double) $value;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getBalanceAttribute()
    {
        // Opening Balance
        $total = $this->opening_balance;

        // Sum Incomes
        $total += $this->revenues->sum('amount');

        // Subtract Expenses
        $total -= $this->payments->sum('amount');

        return $total;
    }
}
