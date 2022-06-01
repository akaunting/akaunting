<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Common\ItemTax;
use App\Http\Resources\Setting\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class Item extends JsonResource
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
            'type' => $this->type,
            'name' => $this->name,
            'description' => $this->description,
            'sale_price' => $this->sale_price,
            'sale_price_formatted' => money($this->sale_price, setting('default.currency'), true)->format(),
            'purchase_price' => $this->purchase_price,
            'purchase_price_formatted' => money($this->purchase_price, setting('default.currency'), true)->format(),
            'category_id' => $this->category_id,
            'picture' => $this->picture,
            'enabled' => $this->enabled,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'taxes' => [static::$wrap => ItemTax::collection($this->taxes)],
            'category' => new Category($this->category),
        ];
    }
}
