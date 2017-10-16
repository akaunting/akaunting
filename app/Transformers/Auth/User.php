<?php

namespace App\Transformers\Auth;

use App\Transformers\Company\Company;
use App\Models\Auth\User as Model;
use League\Fractal\TransformerAbstract;

class User extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['companies', 'roles'];

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
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCompanies(Model $model)
    {
        return $this->collection($model->companies, new Company());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(Model $model)
    {
        return $this->collection($model->roles, new Role());
    }
}
