<?php

namespace App\Exports\Sales\Invoices\Sheets;

use App\Abstracts\Export;
use App\Models\Document\DocumentItem as Model;
use App\Interfaces\Export\WithParentSheet;

class InvoiceItems extends Export implements WithParentSheet
{
    public function collection()
    {
        return Model::with('document', 'item')->invoice()->collectForExport($this->ids, null, 'document_id');
    }

    public function map($model): array
    {
        $document = $model->document;

        if (empty($document)) {
            return [];
        }

        $model->invoice_number = $document->document_number;
        $model->item_name = $model->item->name;
        $model->item_description = $model->item->description;
        $model->item_type = $model->item->type;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'invoice_number',
            'item_name',
            'item_description',
            'item_type',
            'quantity',
            'discount_type',
            'discount_rate',
            'price',
            'total',
            'tax',
        ];
    }
}
