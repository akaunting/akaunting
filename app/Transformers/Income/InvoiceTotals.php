<?php

namespace App\Transformers\Income;

use App\Models\Income\InvoiceTotal as Model;
use League\Fractal\TransformerAbstract;

class InvoiceTotals extends TransformerAbstract
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
            'invoice_id' => $model->invoice_id,
            'code' => $model->code,
            'name' => $model->name,
            'amount' => $model->amount,
            'sort_order' => $model->sort_order,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }
}
