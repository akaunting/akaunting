<?php

namespace App\Transformers\Auth;

use App\Models\Auth\Role as Model;
use League\Fractal\TransformerAbstract;

class Role extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['permissions'];

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

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includePermissions(Model $model)
    {
        return $this->collection($model->permissions, new Permission());
    }
}
