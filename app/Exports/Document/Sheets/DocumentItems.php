<?php

namespace App\Exports\Document\Sheets;

use App\Abstracts\Export;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem as Model;
use Illuminate\Support\Str;

class DocumentItems extends Export
{
    public function collection()
    {
        $model = Model::{$this->type}()->with('document', 'item')->usingSearchString(request('search'));

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

        if ($this->type === Document::INVOICE_TYPE) {
            $model->invoice_number = $document->document_number;
        } else {
            $model->bill_number = $document->document_number;
        }

        $model->item_name = $model->item->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            $this->type === Document::INVOICE_TYPE ? 'invoice_number' : 'bill_number',
            'item_name',
            'quantity',
            'price',
            'total',
            'tax',
        ];
    }

    public function title(): string
    {
        return Str::replaceFirst('document', $this->type, parent::title());
    }
}
