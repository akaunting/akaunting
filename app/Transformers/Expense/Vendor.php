<?php

namespace App\Transformers\Expense;

use App\Models\Expense\Vendor as Model;
use League\Fractal\TransformerAbstract;

class Vendor extends TransformerAbstract
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
            'user_id' => $model->user_id,
            'name' => $model->name,
            'email' => $model->email,
            'tax_number' => $model->tax_number,
            'phone' => $model->phone,
            'address' => $model->address,
            'website' => $model->website,
            'currency_code' => $model->currency_code,
            'enabled' => $model->enabled,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }
}
