<?php

namespace App\Transformers\Common;

use App\Models\Common\Contact as Model;
use League\Fractal\TransformerAbstract;

class Contact extends TransformerAbstract
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
            'type' => $model->type,
            'name' => $model->name,
            'email' => $model->email,
            'tax_number' => $model->tax_number,
            'phone' => $model->phone,
            'address' => $model->address,
            'website' => $model->website,
            'currency_code' => $model->currency_code,
            'enabled' => $model->enabled,
            'reference' => $model->reference,
            'created_by' => $model->created_by,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}
