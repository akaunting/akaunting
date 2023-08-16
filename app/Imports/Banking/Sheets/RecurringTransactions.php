<?php

namespace App\Imports\Banking\Sheets;

use App\Abstracts\Import;
use App\Models\Document\Document;
use App\Models\Banking\Transaction as Model;
use App\Http\Requests\Banking\Transaction as Request;

class RecurringTransactions extends Import
{
    public $request_class = Request::class;

    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $transaction_type = str_replace('-recurring', '', $row['type']);

        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['account_id'] = $this->getAccountId($row);
        $row['category_id'] = $this->getCategoryId($row, $transaction_type);
        $row['contact_id'] = $this->getContactId($row, $transaction_type);

        if ($transaction_type == 'income') {
            $row['document_id'] = Document::invoiceRecurring()->number($row['invoice_bill_number'])->pluck('id')->first();
        } else {
            $row['document_id'] = Document::billRecurring()->number($row['invoice_bill_number'])->pluck('id')->first();
        }
        
        return $row;
    }
}
