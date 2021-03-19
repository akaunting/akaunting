<?php

namespace App\Models\Banking;

use App\Abstracts\Model;
use App\Traits\Currencies;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Znck\Eloquent\Traits\BelongsToThrough;

class Transfer extends Model
{
    use BelongsToThrough, Currencies, HasFactory;

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
        return $this->belongsToThrough(
            'App\Models\Banking\Account',
            'App\Models\Banking\Transaction',
            null,
            '',
            ['App\Models\Banking\Transaction' => 'expense_transaction_id']
        )->withDefault(['name' => trans('general.na')]);
    }

    public function income_transaction()
    {
        return $this->belongsTo('App\Models\Banking\Transaction', 'income_transaction_id');
    }

    public function income_account()
    {
        return $this->belongsToThrough(
            'App\Models\Banking\Account',
            'App\Models\Banking\Transaction',
            null,
            '',
            ['App\Models\Banking\Transaction' => 'income_transaction_id']
        )->withDefault(['name' => trans('general.na')]);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\Transfer::new();
    }
}
