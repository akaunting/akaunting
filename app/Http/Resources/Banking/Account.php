<?php

namespace App\Http\Resources\Banking;

use Illuminate\Http\Resources\Json\JsonResource;

class Account extends JsonResource
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
            'number' => $this->number,
            'currency_code' => $this->currency_code,
            'opening_balance' => $this->opening_balance,
            'opening_balance_formatted' => money($this->opening_balance, $this->currency_code)->format(),
            'current_balance' => $this->balance,
            'current_balance_formatted' => money($this->balance, $this->currency_code)->format(),
            'bank_name' => $this->bank_name,
            'bank_phone' => $this->bank_phone,
            'bank_address' => $this->bank_address,
            'enabled' => $this->enabled,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
