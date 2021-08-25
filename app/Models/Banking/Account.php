<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Traits\Transactions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Bkwld\Cloner\Cloneable;

class Account extends Model
{
    use Cloneable, HasFactory, Transactions;

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
    protected $fillable = ['company_id', 'name', 'number', 'currency_code', 'opening_balance', 'bank_name', 'bank_phone', 'bank_address', 'enabled', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'opening_balance' => 'double',
        'enabled' => 'boolean',
    ];

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

    public function expense_transactions()
    {
        return $this->transactions()->whereIn('type', (array) $this->getExpenseTypes());
    }

    public function income_transactions()
    {
        return $this->transactions()->whereIn('type', (array) $this->getIncomeTypes());
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }

    public function scopeName($query, $name)
    {
        return $query->where('name', '=', $name);
    }

    public function scopeNumber($query, $number)
    {
        return $query->where('number', '=', $number);
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
        $total += $this->income_transactions->sum('amount');

        // Subtract Expenses
        $total -= $this->expense_transactions->sum('amount');

        return $total;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getIncomeBalanceAttribute()
    {
        // Opening Balance
        //$total = $this->opening_balance;
        $total = 0;

        // Sum Incomes
        $total += $this->income_transactions->sum('amount');

        return $total;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getExpenseBalanceAttribute()
    {
        // Opening Balance
        //$total = $this->opening_balance;
        $total = 0;

        // Subtract Expenses
        $total += $this->expense_transactions->sum('amount');

        return $total;
    }


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Account::new();
    }
}
