<?php

namespace App\Imports\Sales\Invoices\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;

class InvoiceTransactions extends Import
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
        if ($this->isEmpty($row, 'invoice_number')) {
            return [];
        }

        $row['invoice_number'] = (string) $row['invoice_number'];

        $row = parent::map($row);

        $row['type'] = 'income';
        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = $this->getCategoryId($row, 'income');
        $row['contact_id'] = $this->getContactId($row, 'customer');
        $row['document_id'] = $this->getDocumentId($row);
        $row['number'] = $row['transaction_number'];

        return $row;
    }

    public function prepareRules(array $rules): array
    {
        $rules['invoice_number'] = 'required|string';

        return $rules;
    }
}
