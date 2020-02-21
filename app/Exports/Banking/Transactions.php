<?php

namespace App\Exports\Banking;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;

class Transactions extends Export
{
    public function collection()
    {
        $model = Model::with(['account', 'bill', 'category', 'contact', 'invoice'])->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        $model->account_name = $model->account->name;
        $model->contact_email = $model->contact->email;
        $model->category_name = $model->category->name;

        if ($model->type == 'income') {
            $model->invoice_bill_number = $model->invoice ? $model->invoice->invoice_number : 0;
        } else {
            $model->invoice_bill_number = $model->bill ? $model->bill->bill_number : 0;
        }

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'type',
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'account_name',
            'invoice_bill_number',
            'contact_email',
            'category_name',
            'description',
            'payment_method',
            'reference',
            'reconciled',
        ];
    }
}
