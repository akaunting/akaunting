<?php

namespace App\Transformers\Document;

use App\Models\Document\DocumentItem as Model;
use League\Fractal\TransformerAbstract;

class DocumentItem extends TransformerAbstract
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
            'item_id' => $model->item_id,
            'name' => $model->name,
            'price' => $model->price,
            'price_formatted' => money($model->price, $model->document->currency_code, true)->format(),
            'total' => $model->total,
            'total_formatted' => money($model->total, $model->document->currency_code, true)->format(),
            'tax' => $model->tax,
            'tax_id' => $model->tax_id,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}
