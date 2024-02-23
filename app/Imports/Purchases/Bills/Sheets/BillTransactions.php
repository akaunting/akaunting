<?php

namespace App\Imports\Purchases\Bills\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;

class BillTransactions extends Import
{
    public $request_class = Request::class;

    public $model = Model::class;

    public $columns = [
        'type',
        'number',
    ];

    public function model(array $row)
    {
        if (self::hasRow($row)) {
            return;
        }
        
        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, 'bill_number')) {
            return [];
        }

        $row['bill_number'] = (string) $row['bill_number'];

        $row = parent::map($row);

        $row['type'] = 'expense';
        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = $this->getCategoryId($row, 'expense');
        $row['contact_id'] = $this->getContactId($row, 'vendor');
        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['document_id'] = $this->getDocumentId($row);
        $row['number'] = $row['transaction_number'];

        return $row;
    }

    public function prepareRules(array $rules): array
    {
        $rules['bill_number'] = 'required|string';

        return $rules;
    }
}
