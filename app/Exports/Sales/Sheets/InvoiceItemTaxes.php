<?php

namespace App\Exports\Sales\Sheets;

use App\Abstracts\Export;
use App\Models\Sale\InvoiceItemTax as Model;

class InvoiceItemTaxes extends Export
{
    public function collection()
    {
        $model = Model::with(['invoice', 'item', 'tax'])->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('invoice_id', (array) $this->ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        $invoice = $model->invoice;

        if (empty($invoice)) {
            return [];
        }

        $model->invoice_number = $invoice->invoice_number;
        $model->item_name = $model->item->name;
        $model->tax_rate = $model->tax->rate;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'invoice_number',
            'item_name',
            'tax_rate',
            'amount',
        ];
    }
}
