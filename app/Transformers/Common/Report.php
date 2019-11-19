<?php

namespace App\Transformers\Common;

use App\Models\Common\Report as Model;
use League\Fractal\TransformerAbstract;

class Report extends TransformerAbstract
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
            'alias' => $model->alias,
            'description' => $model->description,
            'type' => $model->type,
            'group' => $model->group,
            'period' => $model->period,
            'basis' => $model->basis,
            'graph' => $model->graph,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}
