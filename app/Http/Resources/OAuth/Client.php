<?php

namespace App\Http\Resources\OAuth;

use Illuminate\Http\Resources\Json\JsonResource;

class Client extends JsonResource
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
            'id'                     => $this->id,
            'company_id'             => $this->company_id,
            'user_id'                => $this->user_id,
            'name'                   => $this->name,
            'redirect'               => $this->redirect,
            'personal_access_client' => $this->personal_access_client,
            'password_client'        => $this->password_client,
            'revoked'                => $this->revoked,
            'confidential'           => $this->isConfidentialClient(),
            'created_from'           => $this->created_from,
            'created_by'             => $this->created_by,
            'created_at'             => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at'             => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
