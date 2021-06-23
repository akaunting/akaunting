<?php

namespace App\Transformers\Banking;

use App\Models\Banking\Reconciliation as Model;
use League\Fractal\TransformerAbstract;

class Reconciliation extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['account'];

    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'account_id' => $model->account_id,
            'started_at' => $model->started_at->toIso8601String(),
            'ended_at' => $model->ended_at->toIso8601String(),
            'closing_balance' => $model->closing_balance,
            'closing_balance_formatted' => money($model->closing_balance, setting('default.currency'), true)->format(),
            'reconciled' => $model->reconciled,
            'created_by' => $model->created_by,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }

    /**
     * @param  Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeAccount(Model $model)
    {
        return $this->item($model->account, new Account());
    }
}
