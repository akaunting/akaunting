<?php

namespace App\Exports\Purchases\Sheets;

use App\Abstracts\Export;
use App\Models\Document\DocumentHistory as Model;

class BillHistories extends Export
{
    public function collection()
    {
        return Model::with('document')->bill()->collectForExport($this->ids, null, 'document_id');
    }

    public function map($model): array
    {
        $document = $model->document;

        if (empty($document)) {
            return [];
        }

        $model->bill_number = $document->document_number;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'bill_number',
            'status',
            'notify',
            'description',
        ];
    }
}
