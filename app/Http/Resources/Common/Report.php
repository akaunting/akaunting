<?php

namespace App\Http\Resources\Common;

use App\Utilities\Reports as Utility;
use Illuminate\Http\Resources\Json\JsonResource;

class Report extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'class' => $this->class,
            'name' => $this->name,
            'description' => $this->description,
            'settings' => $this->settings,
            'data' => $this->getReportData(),
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }

    protected function getReportData()
    {
        if (! Utility::canShow($this->class)) {
            return [];
        }

        $report = Utility::getClassInstance($this);

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
