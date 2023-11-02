<?php

namespace App\Http\Resources\Banking;

use App\Http\Resources\Banking\Account;
use Illuminate\Http\Resources\Json\JsonResource;

class Reconciliation extends JsonResource
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
            'account_id' => $this->account_id,
            'started_at' => $this->started_at->toIso8601String(),
            'ended_at' => $this->ended_at->toIso8601String(),
            'closing_balance' => $this->closing_balance,
            'closing_balance_formatted' => money($this->closing_balance)->format(),
            'reconciled' => $this->reconciled,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'account' => new Account($this->account),
        ];
    }
}
