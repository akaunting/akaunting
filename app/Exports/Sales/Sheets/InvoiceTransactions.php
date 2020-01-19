<?php

namespace App\Exports\Sales\Sheets;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;

class InvoiceTransactions extends Export
{
    public function collection()
    {
        $model = Model::type('income')->isDocument()->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('document_id', (array) $this->ids);
        }

        return $model->get();
    }

    public function fields(): array
    {
        return [
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'account_id',
            'document_id',
            'contact_id',
            'category_id',
            'description',
            'payment_method',
            'reference',
            'reconciled',
        ];
    }
}
