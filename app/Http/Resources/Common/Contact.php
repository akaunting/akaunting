<?php

namespace App\Http\Resources\Common;

use App\Http\Resources\Common\ContactPerson;
use Illuminate\Http\Resources\Json\JsonResource;

class Contact extends JsonResource
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
            'user_id' => $this->user_id,
            'type' => $this->type,
            'name' => $this->name,
            'email' => $this->email,
            'tax_number' => $this->tax_number,
            'phone' => $this->phone,
            'address' => $this->address,
            'website' => $this->website,
            'currency_code' => $this->currency_code,
            'enabled' => $this->enabled,
            'reference' => $this->reference,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'contact_persons' => [static::$wrap => ContactPerson::collection($this->contact_persons)],
        ];
    }
}
