<?php

namespace App\Imports\Purchases\Bills\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\DocumentHistory as Request;
use App\Models\Document\Document;
use App\Models\Document\DocumentHistory as Model;

class BillHistories extends Import
{
    public $request_class = Request::class;

    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, 'bill_number')) {
            return [];
        }

        $row['bill_number'] = (string) $row['bill_number'];

        $row = parent::map($row);

        $row['document_id'] = (int) Document::bill()->number($row['bill_number'])->pluck('id')->first();

        $row['notify'] = (int) $row['notify'];

        $row['type'] = Document::BILL_TYPE;

        return $row;
    }

    public function prepareRules(array $rules): array
    {
        $rules['bill_number'] = 'required|string';

        unset($rules['bill_id']);

        return $rules;
    }
}
