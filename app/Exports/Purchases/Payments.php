<?php

namespace App\Exports\Purchases;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;

class Payments extends Export
{
    public function collection()
    {
        $model = Model::with(['account', 'bill', 'category', 'contact'])->expense()->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $model->account_name = $model->account->name;
        $model->bill_number = $model->bill ? $model->bill->bill_number : 0;
        $model->contact_email = $model->contact->email;
        $model->category_name = $model->category->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'account_name',
            'bill_number',
            'contact_email',
            'category_name',
            'description',
            'payment_method',
            'reference',
            'reconciled',
        ];
    }
}
