<?php

namespace App\Transformers\Income;

use App\Transformers\Banking\Account;
use App\Transformers\Income\Customer;
use App\Transformers\Setting\Category;
use App\Transformers\Setting\Currency;
use App\Models\Income\Revenue as Model;
use League\Fractal\TransformerAbstract;

class Revenue extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['account', 'category', 'currency', 'customer'];

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
            'customer_id' => $model->customer_id,
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
    public function includeCustomer(Model $model)
    {
        if (!$model->customer) {
            return $this->null();
        }

        return $this->item($model->customer, new Customer());
    }
}
