<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Purchase\BillItem as Request;
use App\Models\Purchase\Bill;
use App\Models\Purchase\BillItem as Model;

class BillItems extends Import
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

        $row['bill_id'] = (int) Bill::number($row['bill_number'])->pluck('id')->first();

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

        $rules['bill_number'] = 'required|string';
        unset($rules['bill_id']);

        return $rules;
    }
}
