<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\DocumentTotal as Request;
use App\Models\Document\Document;
use App\Models\Document\DocumentTotal as Model;

class InvoiceTotals extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, 'invoice_number')) {
            return [];
        }

        $row = parent::map($row);

        $row['document_id'] = (int) Document::invoice()->number($row['invoice_number'])->pluck('id')->first();
        $row['type'] = Document::INVOICE_TYPE;

        return $row;
    }

    public function rules(): array
    {
        $rules = (new Request())->rules();

        $rules['invoice_number'] = 'required|string';

        unset($rules['invoice_id']);

        return $rules;
    }
}
