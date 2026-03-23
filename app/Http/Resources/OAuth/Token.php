<?php

namespace App\Http\Resources\OAuth;

use Illuminate\Http\Resources\Json\JsonResource;

class Token extends JsonResource
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
            'company_id'   => $this->company_id,
            'user_id'      => $this->user_id,
            'client_id'    => $this->client_id,
            'name'         => $this->name,
            'scopes'       => $this->scopes,
            'revoked'      => $this->revoked,
            'created_from' => $this->created_from,
            'created_by'   => $this->created_by,
            'expires_at'   => $this->expires_at ? $this->expires_at->toIso8601String() : null,
            'created_at'   => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at'   => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
