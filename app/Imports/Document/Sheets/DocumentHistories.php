<?php

namespace App\Imports\Document\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\DocumentHistory as Request;
use App\Models\Document\Document;
use App\Models\Document\DocumentHistory as Model;

class DocumentHistories extends Import
{
    public function model(array $row)
    {
        // @todo remove after laravel-excel 3.2 release
        if ($row[$this->type . '_number'] == $this->empty_field) {
            return null;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, $this->type . '_number')) {
            return [];
        }

        $row = parent::map($row);

        $row['document_id'] = (int) Document::{$this->type}()->number($row[$this->type . '_number'])->pluck('id')->first();

        $row['notify'] = (int) $row['notify'];

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
