<?php

namespace App\Imports\Document\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\DocumentItem as Request;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem as Model;

class DocumentItems extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, $this->type . '_number')) {
            return [];
        }

        $row = parent::map($row);

        $row['document_id'] = (int) Document::{$this->type}()->number($row[$this->type . '_number'])->pluck('id')->first();

        if (empty($row['item_id']) && !empty($row['item_name'])) {
            $row['item_id'] = $this->getItemIdFromName($row);

            $row['name'] = $row['item_name'];
        }

        $row['tax'] = (double) $row['tax'];
        $row['tax_id'] = 0;
        $row['type'] = $this->type;

        return $row;
    }

    public function rules(): array
    {
        $rules = (new Request())->rules();

        if ($this->type === Document::INVOICE_TYPE) {
            $rules['invoice_number'] = 'required|string';
        } else {
            $rules['bill_number'] = 'required|string';
        }

        unset($rules['invoice_id'], $rules['bill_id']);

        return $rules;
    }
}
