<?php

namespace App\Http\Resources\Common;

use App\Events\Api\ResourceShowing;
use App\Http\Resources\Auth\Owner;
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

        $resources = [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'name' => $this->name,
            'enabled' => $this->enabled,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'created_from' => $this->created_from,
            'owner' => Owner::from($this->owner),
            'widgets' => [static::$wrap => Widget::collection($widgets)],
        ];

        $event = new ResourceShowing($this->resource);
        event($event);

        if (! empty($event->resources)) {
            $resources = array_merge($resources, $event->resources);
        }

        return $resources;
    }
}
