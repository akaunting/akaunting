<?php

namespace App\Http\Resources\Auth;

use App\Http\Resources\Auth\Role;
use App\Http\Resources\Common\Company;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'locale' => $this->locale,
            'landing_page' => $this->landing_page,
            'enabled' => $this->enabled,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'last_logged_in_at' => $this->last_logged_in_at ? $this->last_logged_in_at->toIso8601String() : '',
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'companies' => [static::$wrap => Company::collection($this->companies)],
            'roles' => [static::$wrap => Role::collection($this->roles)],
        ];
    }
}
