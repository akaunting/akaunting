<?php

namespace App\Transformers\Auth;

use App\Models\Auth\Permission as Model;
use League\Fractal\TransformerAbstract;

class Permission extends TransformerAbstract
{
    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->display_name,
            'code' => $model->name,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}
