<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Common\Widget;
use App\Utilities\Widgets;
use Illuminate\Http\Resources\Json\JsonResource;

class Dashboard extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $widgets = $this->widgets->filter(function ($widget) {
            return Widgets::canShow($widget->class);
        });

        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'enabled' => $this->enabled,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'widgets' => [static::$wrap => Widget::collection($widgets)],
        ];
    }
}
