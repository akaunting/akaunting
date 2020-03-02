<?php

namespace App\Exports\Sales\Sheets;

use App\Abstracts\Export;
use App\Models\Sale\InvoiceTotal as Model;

class InvoiceTotals extends Export
{
    public function collection()
    {
        $model = Model::with(['invoice'])->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('invoice_id', (array) $this->ids);
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

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'invoice_number',
            'code',
            'name',
            'amount',
            'sort_order',
        ];
    }
}
