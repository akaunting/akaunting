<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Traits\Transactions;
use App\Utilities\Str;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use Cloneable, HasFactory, Transactions;

    protected $table = 'accounts';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['balance', 'title', 'initials'];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'type', 'name', 'number', 'currency_code', 'opening_balance', 'bank_name', 'bank_phone', 'bank_address', 'enabled', 'created_from', 'created_by'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'opening_balance'   => 'double',
        'enabled'           => 'boolean',
        'deleted_at'        => 'datetime',
    ];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['name', 'number', 'balance', 'bank_name', 'bank_phone'];

    public function currency()
    {
        return $this->belongsTo('App\Models\Setting\Currency', 'currency_code', 'code');
    }

    public function expense_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getExpenseTypes());
    }

    public function income_transactions()
    {
        return $this->transactions()->whereIn('transactions.type', (array) $this->getIncomeTypes());
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\Banking\Transaction');
    }

    public function reconciliations()
    {
        return $this->hasMany('App\Models\Banking\Reconciliation');
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
     * Sort by balance
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $direction
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function balanceSortable($query, $direction)
    {
        return $query//->join('transactions', 'transactions.account_id', '=', 'accounts.id')
            ->orderBy('balance', $direction)
            ->select(['accounts.*', 'accounts.opening_balance as balance']);
    }

    /**
     * Get the name with currency.
     *
     * @return string
     */
    public function getTitleAttribute()
    {
        if (! empty($this->currency) && ! empty($this->currency->symbol)) {
            return $this->name . ' (' . $this->currency->symbol . ')';
        }

        return $this->name;
    }

    public function getInitialsAttribute($value)
    {
        return Str::getInitials($this->name);
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
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        $actions[] = [
            'title' => trans('general.show'),
            'icon' => 'visibility',
            'url' => route('accounts.show', $this->id),
            'permission' => 'read-banking-accounts',
            'attributes' => [
                'id' => 'index-line-actions-show-account-' . $this->id,
            ],
        ];

        $actions[] = [
            'title' => trans('general.edit'),
            'icon' => 'edit',
            'url' => route('accounts.edit', $this->id),
            'permission' => 'update-banking-accounts',
            'attributes' => [
                'id' => 'index-line-actions-edit-account-' . $this->id,
            ],
        ];

        $actions[] = [
            'type' => 'delete',
            'icon' => 'delete',
            'route' => 'accounts.destroy',
            'permission' => 'delete-banking-accounts',
            'model' => $this,
            'attributes' => [
                'id' => 'index-line-actions-delete-account-' . $this->id,
            ],
        ];

        return $actions;
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
