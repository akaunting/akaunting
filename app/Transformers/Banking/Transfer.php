<?php

namespace App\Transformers\Banking;

use App\Transformers\Expense\Payment;
use App\Transformers\Income\Revenue;
use App\Models\Banking\Transfer as Model;
use League\Fractal\TransformerAbstract;

class Transfer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['payment', 'revenue'];

    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'payment_id' => $model->payment_id,
            'revenue_id' => $model->revenue_id,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includePayment(Model $model)
    {
        return $this->item($model->payment, new Payment());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeRevenue(Model $model)
    {
        return $this->item($model->revenue, new Revenue());
    }
}
