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

        return $model->get();
    }

    public function map($model): array
    {
        $model->invoice_number = $model->invoice->invoice_number;

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
