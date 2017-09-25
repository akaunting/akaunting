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

    public function canDelete()
    {
        $error = false;

        if ($bill_payments = $this->bill_payments()->count()) {
            $error['bills'] = $bill_payments;
        }

        if ($payments = $this->payments()->count()) {
            $error['payments'] = $payments;
        }

        if ($invoice_payments = $this->invoice_payments()->count()) {
            $error['invoices'] = $invoice_payments;
        }

        if ($revenues = $this->revenues()->count()) {
            $error['revenues'] = $revenues;
        }

        if ($error) {
            return $error;
        }

        return true;
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

        // Sum invoices
        foreach ($this->invoice_payments as $item) {
            $total += $item->amount;
        }

        // Sum revenues
        foreach ($this->revenues as $item) {
            $total += $item->amount;
        }

        // Subtract bills
        foreach ($this->bill_payments as $item) {
            $total -= $item->amount;
        }

        // Subtract payments
        foreach ($this->payments as $item) {
            $total -= $item->amount;
        }

        return $total;
    }
}
