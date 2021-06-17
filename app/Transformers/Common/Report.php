<?php

namespace App\Transformers\Common;

use App\Models\Common\Report as Model;
use App\Utilities\Reports as Utility;
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
            'class' => $model->class,
            'name' => $model->name,
            'description' => $model->description,
            'settings' => $model->settings,
            'data' => $this->getReportData($model),
            'created_by' => $model->created_by,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }

    protected function getReportData($model)
    {
        if (!Utility::canShow($model->class)) {
            return [];
        }

        $report = Utility::getClassInstance($model);

        if (empty($report)) {
            return [];
        }

        $unset_attributes = ['model', 'views', 'loaded', 'column_name_width', 'column_value_width'];

        foreach ($unset_attributes as $attribute) {
            unset($report->$attribute);
        }

        return $report;
    }
}
