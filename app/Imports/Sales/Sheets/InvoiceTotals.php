<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Sale\InvoiceTotal as Request;
use App\Models\Sale\Invoice;
use App\Models\Sale\InvoiceTotal as Model;

class InvoiceTotals extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, 'invoice_number')) {
            return [];
        }

        $row = parent::map($row);

        $row['invoice_id'] = (int) Invoice::number($row['invoice_number'])->pluck('id')->first();

        return $row;
    }

    public function rules(): array
    {
        $rules = (new Request())->rules();

        $rules['invoice_number'] = 'required|string';
        unset($rules['invoice_id']);

        return $rules;
    }
}
