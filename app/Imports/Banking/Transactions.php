<?php

namespace App\Imports\Banking;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;
use App\Traits\Transactions as TraitsTransactions;

class Transactions extends Import
{
    use TraitsTransactions;

    public $model = Model::class;

    public $request_class = Request::class;

    public $columns = [
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
        $row = parent::map($row);

        $real_type = $this->getRealTypeTransaction($row['type']);

        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = $this->getCategoryId($row, $real_type);
        $row['contact_id'] = $this->getContactId($row, $real_type);
        $row['document_id'] = $this->getDocumentId($row);
        $row['parent_id'] = $this->getParentId($row) ?? 0;

        return $row;
    }

    public function prepareRules($rules): array
    {
        $rules['number'] = 'required|string';

        return $rules;
    }
}
