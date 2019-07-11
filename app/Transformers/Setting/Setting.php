<?php

namespace App\Transformers\Setting;

use App\Models\Setting\Setting as Model;
use League\Fractal\TransformerAbstract;

class Setting extends TransformerAbstract
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
            'key' => $model->key,
            'value' => $model->value,
        ];
    }
}
