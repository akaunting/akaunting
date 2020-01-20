<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Banking\Transaction as Request;
use App\Models\Banking\Transaction as Model;
use App\Models\Sale\Invoice;

class InvoiceTransactions extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['type'] = 'income';

        if (empty($row['account_id']) && !empty($row['account_name'])) {
            $row['account_id'] = $this->getAccountIdFromName($row);
        }

        if (empty($row['account_id']) && !empty($row['account_number'])) {
            $row['account_id'] = $this->getAccountIdFromNumber($row);
        }

        if (empty($row['account_id']) && !empty($row['currency_code'])) {
            $row['account_id'] = $this->getAccountIdFromCurrency($row);
        }

        if (empty($row['contact_id']) && !empty($row['contact_name'])) {
            $row['contact_id'] = $this->getContactIdFromName($row, 'customer');
        }

        if (empty($row['contact_id']) && !empty($row['contact_email'])) {
            $row['contact_id'] = $this->getContactIdFromEmail($row, 'customer');
        }

        if (empty($row['category_id']) && !empty($row['category_name'])) {
            $row['category_id'] = $this->getCategoryIdFromName($row, 'income');
        }

        $row['document_id'] = Invoice::number($row['invoice_number'])->pluck('id')->first();

        return $row;
    }

    public function rules(): array
    {
        $rules = (new Request())->rules();

        $rules['invoice_number'] = 'required|string';

        return $rules;
    }
}
