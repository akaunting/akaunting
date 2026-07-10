<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\Auth\Owner;
use App\Http\Resources\Setting\Tax;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentItemTax extends JsonResource
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
            'document_id' => $this->document_id,
            'document_item_id' => $this->document_item_id,
            'tax_id' => $this->tax_id,
            'name' => $this->name,
            'amount' => $this->amount,
            'amount_formatted' => money($this->amount, $this->document->currency_code)->format(),
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'created_from' => $this->created_from,
            'owner' => Owner::from($this->owner),
            'tax' => new Tax($this->tax),
        ];
    }
}
