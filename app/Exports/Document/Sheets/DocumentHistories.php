<?php

namespace App\Exports\Document\Sheets;

use App\Abstracts\Export;
use App\Models\Document\Document;
use App\Models\Document\DocumentHistory as Model;
use Illuminate\Support\Str;

class DocumentHistories extends Export
{
    public function collection()
    {
        $model = Model::{$this->type}()->with('document')->usingSearchString(request('search'));

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

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            $this->type === Document::INVOICE_TYPE ? 'invoice_number' : 'bill_number',
            'status',
            'notify',
            'description',
        ];
    }

    public function title(): string
    {
        return Str::replaceFirst('document', $this->type, parent::title());
    }
}
