<?php

namespace App\Http\Resources\Setting;

use Illuminate\Http\Resources\Json\JsonResource;

class Currency extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'rate' => $this->rate,
            'enabled' => $this->enabled,
            'precision' => $this->precision,
            'symbol' => $this->symbol,
            'symbol_first' => $this->symbol_first,
            'decimal_mark' => $this->decimal_mark,
            'thousands_separator' => $this->thousands_separator,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
