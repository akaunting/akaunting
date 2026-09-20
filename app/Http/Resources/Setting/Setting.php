<?php

namespace App\Http\Resources\Setting;

use App\Events\Api\ResourceShowing;
use Illuminate\Http\Resources\Json\JsonResource;

class Setting extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $resources = [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'key' => $this->key,
            'value' => $this->value,
        ];

        $event = new ResourceShowing($this->resource);
        event($event);

        if (! empty($event->resources)) {
            $resources = array_merge($resources, $event->resources);
        }

        return $resources;
    }
}
