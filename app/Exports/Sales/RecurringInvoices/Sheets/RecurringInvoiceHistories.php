<?php

namespace App\Exports\Sales\RecurringInvoices\Sheets;

use App\Abstracts\Export;
use App\Models\Document\DocumentHistory as Model;
use App\Interfaces\Export\WithParentSheet;

class RecurringInvoiceHistories extends Export implements WithParentSheet
{
    public function collection()
    {
        return Model::with('document')->invoiceRecurring()->collectForExport($this->ids, null, 'document_id');
    }

    public function map($model): array
    {
        $document = $model->document;

        if (empty($document)) {
            return [];
        }

        $model->invoice_number = $document->document_number;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'invoice_number',
            'status',
            'notify',
            'description',
        ];
    }
}
