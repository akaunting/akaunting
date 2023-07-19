<?php

namespace App\Http\Resources\Document;

use App\Http\Resources\Banking\Transaction;
use App\Http\Resources\Common\Contact;
use App\Http\Resources\Document\DocumentHistory;
use App\Http\Resources\Document\DocumentItem;
use App\Http\Resources\Document\DocumentItemTax;
use App\Http\Resources\Document\DocumentTotal;
use App\Http\Resources\Setting\Category;
use App\Http\Resources\Setting\Currency;
use Illuminate\Http\Resources\Json\JsonResource;

class Document extends JsonResource
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
            'document_number' => $this->document_number,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'issued_at' => $this->issued_at ? $this->issued_at->toIso8601String() : '',
            'due_at' => $this->due_at ? $this->due_at->toIso8601String() : '',
            'amount' => $this->amount,
            'amount_formatted' => money($this->amount, $this->currency_code)->format(),
            'category_id' => $this->category_id,
            'currency_code' => $this->currency_code,
            'currency_rate' => $this->currency_rate,
            'contact_id' => $this->contact_id,
            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'contact_tax_number' => $this->contact_tax_number,
            'contact_phone' => $this->contact_phone,
            'contact_address' => $this->contact_address,
            'contact_city' => $this->contact_city,
            'contact_zip_code' => $this->contact_zip_code,
            'contact_state' => $this->contact_state,
            'contact_country' => $this->contact_country,
            'notes' => $this->notes,
            'attachment' => $this->attachment,
            'created_from' => $this->created_from,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : '',
            'category' => new Category($this->category),
            'currency' => new Currency($this->currency),
            'contact' => new Contact($this->contact),
            'histories' => [static::$wrap => DocumentHistory::collection($this->histories)],
            'items' => [static::$wrap => DocumentItem::collection($this->items)],
            'item_taxes' => [static::$wrap => DocumentItemTax::collection($this->item_taxes)],
            'totals' => [static::$wrap => DocumentTotal::collection($this->totals)],
            'transactions' => [static::$wrap => Transaction::collection($this->transactions)],
        ];
    }
}
