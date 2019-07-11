<?php

namespace App\Transformers\Expense;

use App\Models\Expense\BillTotal as Model;
use League\Fractal\TransformerAbstract;

class BillTotals extends TransformerAbstract
{

    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'bill_id' => $model->bill_id,
            'code' => $model->code,
            'name' => $model->name,
            'amount' => $model->amount,
            'sort_order' => $model->sort_order,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }
}
