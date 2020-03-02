<?php

namespace App\Exports\Purchases\Sheets;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;

class BillTransactions extends Export
{
    public function collection()
    {
        $model = Model::with(['account', 'category', 'contact', 'bill'])->type('expense')->isDocument()->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('document_id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $bill = $model->bill;

        if (empty($bill)) {
            return [];
        }

        $model->bill_number = $bill->bill_number;
        $model->account_name = $model->account->name;
        $model->category_name = $model->category->name;
        $model->contact_email = $model->contact->email;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'bill_number',
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'account_name',
            'contact_email',
            'category_name',
            'description',
            'payment_method',
            'reference',
            'reconciled',
        ];
    }
}
