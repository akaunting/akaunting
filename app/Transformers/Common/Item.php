<?php

namespace App\Transformers\Common;

use App\Models\Common\Item as Model;
use App\Transformers\Setting\Category;
use League\Fractal\TransformerAbstract;

class Item extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['taxes', 'category'];

    /**
     * @param  Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'name' => $model->name,
            'description' => $model->description,
            'sale_price' => $model->sale_price,
            'sale_price_formatted' => money($model->sale_price, setting('default.currency'), true)->format(),
            'purchase_price' => $model->purchase_price,
            'purchase_price_formatted' => money($model->purchase_price, setting('default.currency'), true)->format(),
            'category_id' => $model->category_id,
            'tax_ids' => $model->tax_ids,
            'picture' => $model->picture,
            'enabled' => $model->enabled,
            'created_by' => $model->created_by,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }

    /**
     * @param  Model $model
     * @return mixed
     */
    public function includeTaxes(Model $model)
    {
        if (!$model->taxes) {
            return $this->null();
        }

        return $this->collection($model->taxes, new ItemTax());
    }

    /**
     * @param  Model $model
     * @return mixed
     */
    public function includeCategory(Model $model)
    {
        if (!$model->category) {
            return $this->null();
        }

        return $this->item($model->category, new Category());
    }
}
