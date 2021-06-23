<?php

namespace App\Transformers\Document;

use App\Models\Document\DocumentItemTax as Model;
use App\Transformers\Setting\Tax;
use League\Fractal\TransformerAbstract;

class DocumentItemTax extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['tax'];

    /**
     * @param  Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'type' => $model->type,
            'document_id' => $model->document_id,
            'document_item_id' => $model->document_item_id,
            'tax_id' => $model->tax_id,
            'name' => $model->name,
            'amount' => $model->amount,
            'amount_formatted' => money($model->amount, $model->document->currency_code, true)->format(),
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }

    /**
     * @param  Model $model
     * @return mixed
     */
    public function includeTax(Model $model)
    {
        if (!$model->tax) {
            return $this->null();
        }

        return $this->item($model->tax, new Tax());
    }
}
