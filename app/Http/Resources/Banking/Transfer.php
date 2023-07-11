<?php

namespace App\Http\Resources\Banking;

use Illuminate\Http\Resources\Json\JsonResource;

class Transfer extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $expense_transaction = $this->expense_transaction;
        $income_transaction = $this->income_transaction;

        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'from_account' => $expense_transaction->account->name,
            'from_account_id' => $expense_transaction->account->id,
            'to_account' => $income_transaction->account->name,
            'to_account_id' => $income_transaction->account->id,
            'amount' => $expense_transaction->amount,
            'amount_formatted' => money($expense_transaction->amount, $expense_transaction->currency_code)->format(),
            'currency_code' => $expense_transaction->currency_code,
            'paid_at' => $expense_transaction->paid_at ? $expense_transaction->paid_at->toIso8601String() : '',
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
        ];
    }
}
