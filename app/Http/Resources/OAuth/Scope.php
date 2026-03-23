<?php

namespace App\Http\Resources\OAuth;

use Illuminate\Http\Resources\Json\JsonResource;

class Scope extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'key'          => $this->key,
            'name'         => $this->name,
            'description'  => $this->description,
            'group'        => $this->group,
            'enabled'      => $this->enabled,
            'is_default'   => $this->is_default,
            'sort_order'   => $this->sort_order,
            'created_from' => $this->created_from,
            'created_by'   => $this->created_by,
            'created_at'   => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at'   => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
