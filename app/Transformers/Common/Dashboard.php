<?php

namespace App\Transformers\Common;

use App\Models\Common\Dashboard as Model;
use League\Fractal\TransformerAbstract;
use App\Utilities\Widgets;

class Dashboard extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['widgets'];

    /**
     * @param  Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'name' => $model->name,
            'enabled' => $model->enabled,
            'created_by' => $model->created_by,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }

    /**
     * @param  Model $model
     * @return mixed
     */
    public function includeWidgets(Model $model)
    {
        if (!$widgets = $model->widgets) {
            return $this->null();
        }

        $widgets = $widgets->filter(function ($widget) {
            return Widgets::canShow($widget->class);
        });

        return $this->collection($widgets, new Widget());
    }
}
