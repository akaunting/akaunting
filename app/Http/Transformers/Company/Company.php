<?php

namespace App\Http\Transformers\Company;

use App\Models\Company\Company as Model;
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
            'name' => $model->company_name,
            'email' => $model->company_email,
            'domain' => $model->domain,
            'address' => $model->company_address,
            'logo' => $model->company_logo,
            'enabled' => $model->enabled,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }
}
