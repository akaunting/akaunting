<?php

namespace App\Models\Banking;

use App\Models\Model;
use Sofa\Eloquence\Eloquence;

class Account extends Model
{
    use Eloquence;

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

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name'         => 10,
        'number'       => 10,
        'bank_name'    => 10,
        'bank_phone'   => 5,
        'bank_address' => 2,
    ];

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function invoice_payments()
    {
        return $this->hasMany('App\Models\Income\InvoicePayment');
    }

    public function revenues()
    {
        return $this->hasMany('App\Models\Income\Revenue');
    }

    public function bill_payments()
    {
        return $this->hasMany('App\Models\Expense\BillPayment');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Expense\Payment');
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
        $total += $this->invoice_payments()->sum('amount') + $this->revenues()->sum('amount');

        // Subtract Expenses
        $total -= $this->bill_payments()->sum('amount') + $this->payments()->sum('amount');

        return $total;
    }
}
