<?php

namespace App\Transformers\Expense;

use App\Models\Expense\BillHistory as Model;
use League\Fractal\TransformerAbstract;

class BillHistories extends TransformerAbstract
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
            'status_code' => $model->status_code,
            'notify' => $model->notify,
            'description' => $model->description,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }
}
