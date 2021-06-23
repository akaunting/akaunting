<?php

namespace App\Transformers\Document;

use App\Models\Document\DocumentTotal as Model;
use League\Fractal\TransformerAbstract;

class DocumentTotal extends TransformerAbstract
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
            'type' => $model->type,
            'document_id' => $model->document_id,
            'code' => $model->code,
            'name' => $model->name,
            'amount' => $model->amount,
            'amount_formatted' => money($model->amount, $model->document->currency_code, true)->format(),
            'sort_order' => $model->sort_order,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}
