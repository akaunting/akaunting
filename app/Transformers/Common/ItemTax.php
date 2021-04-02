<?php

namespace App\Transformers\Common;

use App\Models\Common\ItemTax as Model;
use App\Transformers\Setting\Tax;
use League\Fractal\TransformerAbstract;

class ItemTax extends TransformerAbstract
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
            'item_id' => $model->item_id,
            'tax_id' => $model->tax_id,
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
