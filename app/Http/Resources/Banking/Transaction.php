<?php

namespace App\Http\Resources\Banking;

use App\Http\Resources\Banking\Account;
use App\Http\Resources\Common\Contact;
use App\Http\Resources\Setting\Category;
use App\Http\Resources\Setting\Currency;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Banking\TransactionTax;

class Transaction extends JsonResource
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
            'number' => $this->number,
            'company_id' => $this->company_id,
            'type' => $this->type,
            'account_id' => $this->account_id,
            'paid_at' => $this->paid_at->toIso8601String(),
            'amount' => $this->amount,
            'amount_formatted' => money($this->amount, $this->currency_code)->format(),
            'currency_code' => $this->currency_code,
            'currency_rate' => $this->currency_rate,
            'document_id' => $this->document_id,
            'contact_id' => $this->contact_id,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'payment_method' => $this->payment_method,
            'reference' => $this->reference,
            'parent_id' => $this->parent_id,
            'split_id' => $this->split_id,
            'attachment' => $this->attachment,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'account' => new Account($this->account),
            'category' => new Category($this->category),
            'currency' => new Currency($this->currency),
            'contact' => new Contact($this->contact),
            'taxes' => [static::$wrap => TransactionTax::collection($this->taxes)],
        ];
    }
}
