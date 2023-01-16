<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\DocumentHistory as Request;
use App\Models\Document\Document;
use App\Models\Document\DocumentHistory as Model;

class InvoiceHistories extends Import
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

        $row['notify'] = (int) $row['notify'];

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
