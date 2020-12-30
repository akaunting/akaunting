<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;

class BillTransactions extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, 'bill_number')) {
            return [];
        }

        $row = parent::map($row);

        $row['type'] = 'expense';
        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = $this->getCategoryId($row, 'expense');
        $row['contact_id'] = $this->getContactId($row, 'vendor');
        $row['document_id'] = $this->getDocumentId($row);

        return $row;
    }

    public function rules(): array
    {
        $rules = (new Request())->rules();

        $rules['bill_number'] = 'required|string';

        return $rules;
    }
}
