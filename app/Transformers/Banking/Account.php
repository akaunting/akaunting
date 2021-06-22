<?php

namespace App\Transformers\Banking;

use App\Models\Banking\Account as Model;
use League\Fractal\TransformerAbstract;

class Account extends TransformerAbstract
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
            'name' => $model->name,
            'number' => $model->number,
            'currency_code' => $model->currency_code,
            'opening_balance' => $model->opening_balance,
            'opening_balance_formatted' => money($model->opening_balance, $model->currency_code, true)->format(),
            'current_balance' => $model->balance,
            'current_balance_formatted' => money($model->balance, $model->currency_code, true)->format(),
            'bank_name' => $model->bank_name,
            'bank_phone' => $model->bank_phone,
            'bank_address' => $model->bank_address,
            'enabled' => $model->enabled,
            'created_by' => $model->created_by,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}
