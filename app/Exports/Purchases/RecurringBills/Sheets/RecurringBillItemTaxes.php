<?php

namespace App\Exports\Purchases\RecurringBills\Sheets;

use App\Abstracts\Export;
use App\Models\Document\DocumentItemTax as Model;

class RecurringBillItemTaxes extends Export
{
    public function collection()
    {
        return Model::with('document', 'item', 'tax')->billRecurring()->collectForExport($this->ids, null, 'document_id');
    }

    public function map($model): array
    {
        $document = $model->document;

        if (empty($document)) {
            return [];
        }

        $model->bill_number = $document->document_number;
        $model->item_name = $model->item->name;
        $model->tax_rate = $model->tax->rate;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'bill_number',
            'item_name',
            'tax_rate',
            'amount',
        ];
    }
}
