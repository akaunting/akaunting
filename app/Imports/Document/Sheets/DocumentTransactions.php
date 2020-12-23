<?php

namespace App\Imports\Document\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;
use App\Models\Document\Document;

class DocumentTransactions extends Import
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

        $row['type'] = $this->type === Document::INVOICE_TYPE ? 'income' : 'expense';
        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = $this->getCategoryId($row, $this->type === Document::INVOICE_TYPE ? 'income' : 'expense');
        $row['contact_id'] = $this->getContactId($row, $this->type === Document::INVOICE_TYPE ? 'customer' : 'vendor');
        $row['document_id'] = $this->getDocumentId($row);

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

        return $rules;
    }
}
