<?php

namespace App\Transformers\Income;

use App\Transformers\Income\Customer;
use App\Transformers\Income\InvoiceHistories;
use App\Transformers\Income\InvoiceItems;
use App\Transformers\Income\InvoicePayments;
use App\Transformers\Income\InvoiceStatus;
use App\Transformers\Setting\Currency;
use App\Models\Income\Invoice as Model;
use League\Fractal\TransformerAbstract;

class Invoice extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['currency', 'customer', 'histories', 'items', 'payments', 'status'];

    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'invoice_number' => $model->invoice_number,
            'order_number' => $model->order_number,
            'invoice_status_code' => $model->invoice_status_code,
            'invoiced_at' => $model->invoiced_at->toIso8601String(),
            'due_at' => $model->due_at->toIso8601String(),
            'amount' => $model->amount,
            'currency_code' => $model->currency_code,
            'currency_rate' => $model->currency_rate,
            'customer_id' => $model->customer_id,
            'customer_name' => $model->customer_name,
            'customer_email' => $model->customer_email,
            'customer_tax_number' => $model->customer_tax_number,
            'customer_phone' => $model->customer_phone,
            'customer_address' => $model->customer_address,
            'notes' => $model->notes,
            'attachment' => $model->attachment,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
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
     * @return \League\Fractal\Resource\Item
     */
    public function includeCustomer(Model $model)
    {
        return $this->item($model->customer, new Customer());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeHistories(Model $model)
    {
        return $this->collection($model->histories, new InvoiceHistories());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeItems(Model $model)
    {
        return $this->collection($model->items, new InvoiceItems());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includePayments(Model $model)
    {
        return $this->collection($model->payments, new InvoicePayments());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeStatus(Model $model)
    {
        return $this->item($model->status, new InvoiceStatus());
    }
}
