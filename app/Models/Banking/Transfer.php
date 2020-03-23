<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Traits\Currencies;

class Transfer extends Model
{
    use Currencies;

    protected $table = 'transfers';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id', 'expense_transaction_id', 'income_transaction_id'];

    /**
     * Sortable columns.
     *
     * @var array
     */
    public $sortable = ['expense.paid_at', 'expense.amount', 'expense.name', 'income.name'];

    public function expense_transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'expense_transaction_id');
    }

    public function expense_account()
    {
        return $this->belongsTo('App\Models\Banking\Account', 'expense_transaction.account_id', 'id')->withDefault(['name' => trans('general.na')]);
    }

    public function income_transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'income_transaction_id');
    }

    public function income_account()
    {
        return $this->belongsTo('App\Models\Banking\Account', 'income_transaction.account_id', 'id')->withDefault(['name' => trans('general.na')]);
    }
}
