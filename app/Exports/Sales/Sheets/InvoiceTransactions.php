<?php

namespace App\Exports\Sales\Sheets;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;

class InvoiceTransactions extends Export
{
    public function collection()
    {
        $model = Model::with(['account', 'category', 'contact', 'invoice'])->type('income')->isDocument()->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('document_id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $invoice = $model->invoice;

        if (empty($invoice)) {
            return [];
        }

        $model->invoice_number = $invoice->invoice_number;
        $model->account_name = $model->account->name;
        $model->category_name = $model->category->name;
        $model->contact_email = $model->contact->email;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'invoice_number',
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
