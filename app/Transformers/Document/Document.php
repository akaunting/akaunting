<?php

namespace App\Transformers\Document;

use App\Models\Document\Document as Model;
use App\Transformers\Banking\Transaction;
use App\Transformers\Common\Contact;
use App\Transformers\Setting\Currency;
use League\Fractal\TransformerAbstract;

class Document extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['contact', 'currency', 'histories', 'items', 'item_taxes', 'totals', 'transactions'];

    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'type' => $model->type,
            'document_number' => $model->document_number,
            'order_number' => $model->order_number,
            'status' => $model->status,
            'issued_at' => $model->issued_at ? $model->issued_at->toIso8601String() : '',
            'due_at' => $model->due_at ? $model->due_at->toIso8601String() : '',
            'amount' => $model->amount,
            'amount_formatted' => money($model->amount, $model->currency_code, true)->format(),
            'currency_code' => $model->currency_code,
            'currency_rate' => $model->currency_rate,
            'contact_id' => $model->contact_id,
            'contact_name' => $model->contact_name,
            'contact_email' => $model->contact_email,
            'contact_tax_number' => $model->contact_tax_number,
            'contact_phone' => $model->contact_phone,
            'contact_address' => $model->contact_address,
            'notes' => $model->notes,
            'attachment' => $model->attachment,
            'created_by' => $model->created_by,
            'created_at' => $model->created_at ? $model->created_at->toIso8601String() : '',
            'updated_at' => $model->updated_at ? $model->updated_at->toIso8601String() : '',
        ];
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeContact(Model $model)
    {
        return $this->item($model->contact, new Contact());
    }

    /**
     * @param  Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeCurrency(Model $model)
    {
        return $this->item($model->currency, new Currency());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeHistories(Model $model)
    {
        return $this->collection($model->histories, new DocumentHistory());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeItems(Model $model)
    {
        return $this->collection($model->items, new DocumentItem());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeItemTaxes(Model $model)
    {
        return $this->collection($model->item_taxes, new DocumentItemTax());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeTotals(Model $model)
    {
        return $this->collection($model->totals, new DocumentTotal());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeTransactions(Model $model)
    {
        return $this->collection($model->transactions, new Transaction());
    }
}
