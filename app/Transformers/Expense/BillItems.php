<?php

namespace App\Transformers\Expense;

use App\Models\Expense\BillItem as Model;
use League\Fractal\TransformerAbstract;

class BillItems extends TransformerAbstract
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
            'item_id' => $model->item_id,
            'name' => $model->name,
            'sku' => $model->sku,
            'quantity' => $model->quantity,
            'price' => $model->price,
            'total' => $model->total,
            'tax' => $model->tax,
            'tax_id' => $model->tax_id,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }
}
