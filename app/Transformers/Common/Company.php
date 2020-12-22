<?php

namespace App\Transformers\Common;

use App\Models\Common\Company as Model;
use League\Fractal\TransformerAbstract;

class Company extends TransformerAbstract
{
    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email,
            'currency' => $model->currency,
            'domain' => $model->domain,
            'address' => $model->address,
            'logo' => $model->logo,
            'enabled' => $model->enabled,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}
