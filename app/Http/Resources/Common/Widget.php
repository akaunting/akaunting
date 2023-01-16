<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Resources\Json\JsonResource;

class Widget extends JsonResource
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
            'dashboard_id' => $this->dashboard_id,
            'class' => $this->class,
            'name' => $this->name,
            'sort' => $this->sort,
            'settings' => $this->settings,
            'data' => show_widget($this),
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
