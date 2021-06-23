<?php

namespace App\Transformers\Banking;

use App\Transformers\Common\Contact;
use App\Transformers\Setting\Category;
use App\Transformers\Setting\Currency;
use App\Models\Banking\Transaction as Model;
use League\Fractal\TransformerAbstract;

class Transaction extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $defaultIncludes = ['account', 'category', 'contact', 'currency'];

    /**
     * @param  Model $model
     * @return array
     */
    public function transform(Model $model)
    {
        return [
            'id' => $model->id,
            'company_id' => $model->company_id,
            'type' => $model->type,
            'account_id' => $model->account_id,
            'paid_at' => $model->paid_at->toIso8601String(),
            'amount' => $model->amount,
            'amount_formatted' => money($model->amount, $model->currency_code, true)->format(),
            'currency_code' => $model->currency_code,
            'currency_rate' => $model->currency_rate,
            'document_id' => $model->document_id,
            'contact_id' => $model->contact_id,
            'description' => $model->description,
            'category_id' => $model->category_id,
            'payment_method' => $model->payment_method,
            'reference' => $model->reference,
            'attachment' => $model->attachment,
            'created_by' => $model->created_by,
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
    public function includeContact(Model $model)
    {
        if (!$model->contact) {
            return $this->null();
        }

        return $this->item($model->contact, new Contact());
    }
}
