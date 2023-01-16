<?php

namespace App\Exports\Sales\Sheets;

use App\Abstracts\Export;
use App\Models\Document\DocumentItem as Model;

class InvoiceItems extends Export
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

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'invoice_number',
            'item_name',
            'quantity',
            'price',
            'total',
            'tax',
        ];
    }
}
