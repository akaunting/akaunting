<?php

namespace App\Transformers\Expense;

use App\Transformers\Banking\Account;
use App\Transformers\Expense\Vendor;
use App\Transformers\Setting\Category;
use App\Transformers\Setting\Currency;
use App\Models\Expense\Payment as Model;
use League\Fractal\TransformerAbstract;

class Payment extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['account', 'category', 'currency', 'vendor'];

    /**
     * @param  Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'account_id' => $model->account_id,
            'paid_at' => $model->paid_at->toIso8601String(),
            'amount' => $model->amount,
            'currency_code' => $model->currency_code,
            'currency_rate' => $model->currency_rate,
            'vendor_id' => $model->vendor_id,
            'description' => $model->description,
            'category_id' => $model->category_id,
            'payment_method' => $model->payment_method,
            'reference' => $model->reference,
            'attachment' => $model->attachment,
            'created_at' => $model->created_at->toIso8601String(),
            'updated_at' => $model->updated_at->toIso8601String(),
        ];
    }

    /**
     * @param  Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeAccount(Model $model)
    {
        return $this->item($model->account, new Account());
    }

    /**
     * @param  Model $model
     * @return \League\Fractal\Resource\Item
     */
    public function includeCategory(Model $model)
    {
        return $this->item($model->category, new Category());
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
     * @param  Model $model
     * @return mixed
     */
    public function includeVendor(Model $model)
    {
        if (!$model->vendor) {
            return $this->null();
        }

        return $this->item($model->vendor, new Vendor());
    }
}
