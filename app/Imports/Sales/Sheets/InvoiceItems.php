<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Sale\InvoiceItem as Request;
use App\Models\Sale\Invoice;
use App\Models\Sale\InvoiceItem as Model;

class InvoiceItems extends Import
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

        if (empty($row['item_id']) && !empty($row['item_name'])) {
            $row['item_id'] = $this->getItemIdFromName($row);

            $row['name'] = $row['item_name'];
        }

        $row['tax'] = (double) $row['tax'];
        $row['tax_id'] = 0;

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
