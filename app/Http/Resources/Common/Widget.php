<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Auth\Owner;
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
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'created_from' => $this->created_from,
            'owner' => Owner::from($this->owner),
        ];
    }
}
