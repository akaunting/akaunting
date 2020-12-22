<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Sale\InvoiceHistory as Request;
use App\Models\Sale\Invoice;
use App\Models\Sale\InvoiceHistory as Model;

class InvoiceHistories extends Import
{
    public function model(array $row)
    {
        // @todo remove after laravel-excel 3.2 release
        if ($row['invoice_number'] == $this->empty_field) {
            return null;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, 'invoice_number')) {
            return [];
        }

        $row = parent::map($row);

        $row['invoice_id'] = (int) Invoice::number($row['invoice_number'])->pluck('id')->first();

        $row['notify'] = (int) $row['notify'];

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
