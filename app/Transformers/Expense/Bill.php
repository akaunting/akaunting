<?php

namespace App\Transformers\Expense;

use App\Transformers\Expense\BillHistories;
use App\Transformers\Expense\BillItems;
use App\Transformers\Expense\BillPayments;
use App\Transformers\Expense\BillStatus;
use App\Transformers\Expense\Vendor;
use App\Transformers\Setting\Currency;
use App\Models\Expense\Bill as Model;
use League\Fractal\TransformerAbstract;

class Bill extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['currency', 'histories', 'items', 'payments', 'status', 'vendor'];

    /**
     * @param Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'bill_number' => $model->bill_number,
            'order_number' => $model->order_number,
            'bill_status_code' => $model->invoice_status_code,
            'billed_at' => $model->billed_at->toIso8601String(),
            'due_at' => $model->due_at->toIso8601String(),
            'amount' => $model->amount,
            'currency_code' => $model->currency_code,
            'currency_rate' => $model->currency_rate,
            'vendor_id' => $model->vendor_id,
            'vendor_name' => $model->vendor_name,
            'vendor_email' => $model->vendor_email,
            'vendor_tax_number' => $model->vendor_tax_number,
            'vendor_phone' => $model->vendor_phone,
            'vendor_address' => $model->vendor_address,
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
     * @return \League\Fractal\Resource\Collection
     */
    public function includeHistories(Model $model)
    {
        return $this->collection($model->histories, new BillHistories());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeItems(Model $model)
    {
        return $this->collection($model->items, new BillItems());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includePayments(Model $model)
    {
        return $this->collection($model->payments, new BillPayments());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeStatus(Model $model)
    {
        return $this->item($model->status, new BillStatus());
    }

    /**
     * @param Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeVendor(Model $model)
    {
        return $this->item($model->vendor, new Vendor());
    }
}
