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
            'name' => $model->company_name,
            'email' => $model->company_email,
            'domain' => $model->domain,
            'address' => $model->company_address,
            'logo' => $model->company_logo,
            'default_account' => $model->default_account,
            'default_currency' => $model->default_currency,
            'default_tax' => $model->default_tax,
            'default_payment_method' => $model->default_payment_method,
            'default_language' => $model->default_language,
            'enabled' => $model->enabled,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }
}
