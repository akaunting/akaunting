<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Setting\Tax;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemTax extends JsonResource
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
            'item_id' => $this->item_id,
            'tax_id' => $this->tax_id,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'tax' => new Tax($this->tax),
        ];
    }
}
