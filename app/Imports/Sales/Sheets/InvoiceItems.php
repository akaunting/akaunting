<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\DocumentItem as Request;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem as Model;

class InvoiceItems extends Import
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

        if (empty($row['item_id']) && !empty($row['item_name'])) {
            $row['item_id'] = $this->getItemIdFromName($row);

            $row['name'] = $row['item_name'];
        }

        $row['tax'] = (double) $row['tax'];
        $row['tax_id'] = 0;
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
