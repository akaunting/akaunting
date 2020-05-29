<?php

namespace App\Exports\Sales;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;

class Revenues extends Export
{
    public function collection()
    {
        $model = Model::with(['account', 'category', 'contact', 'invoice'])->income()->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $model->account_name = $model->account->name;
        $model->invoice_number = $model->invoice ? $model->invoice->invoice_number : 0;
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
            'invoice_number',
            'contact_email',
            'category_name',
            'description',
            'payment_method',
            'reference',
            'reconciled',
        ];
    }
}
