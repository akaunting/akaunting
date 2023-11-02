<?php

namespace App\Exports\Purchases\Bills\Sheets;

use App\Abstracts\Export;
use App\Models\Document\DocumentItem as Model;

class BillItems extends Export
{
    public function collection()
    {
        return Model::with('document', 'item')->bill()->collectForExport($this->ids, null, 'document_id');
    }

    public function map($model): array
    {
        $document = $model->document;

        if (empty($document)) {
            return [];
        }

        $model->bill_number = $document->document_number;
        $model->item_name = $model->item->name;
        $model->item_description = $model->item->description;
        $model->item_type = $model->item->type;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'bill_number',
            'item_name',
            'item_description',
            'item_type',
            'quantity',
            'price',
            'total',
            'tax',
        ];
    }
}
