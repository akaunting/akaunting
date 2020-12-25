<?php

namespace App\Exports\Sales\Sheets;

use App\Abstracts\Export;
use App\Models\Document\DocumentHistory as Model;

class InvoiceHistories extends Export
{
    public function collection()
    {
        $model = Model::invoice()->with('document')->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('document_id', (array) $this->ids);
        }

        return $model->cursor();
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
