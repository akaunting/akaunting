<?php

namespace App\Transformers\Banking;

use App\Models\Banking\Transfer as Model;
use League\Fractal\TransformerAbstract;

class Transfer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        $expense_transaction = $model->expense_transaction;
        $income_transaction = $model->income_transaction;

        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'from_account' => $expense_transaction->account->name,
            'from_account_id' => $expense_transaction->account->id,
            'to_account' => $income_transaction->account->name,
            'to_account_id' => $income_transaction->account->id,
            'amount' => $expense_transaction->amount,
            'amount_formatted' => money($expense_transaction->amount, $expense_transaction->currency_code, true)->format(),
            'currency_code' => $expense_transaction->currency_code,
            'paid_at' => $expense_transaction->paid_at ? $expense_transaction->paid_at->toIso8601String() : '',
            'created_by' => $model->created_by,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}
