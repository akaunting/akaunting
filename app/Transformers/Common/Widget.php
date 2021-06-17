<?php

namespace App\Transformers\Common;

use App\Models\Common\Widget as Model;
use League\Fractal\TransformerAbstract;

class Widget extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @param  Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'dashboard_id' => $model->dashboard_id,
            'class' => $model->class,
            'name' => $model->name,
            'sort' => $model->sort,
            'settings' => $model->settings,
            'data' => show_widget($model),
            'created_by' => $model->created_by,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }
}
