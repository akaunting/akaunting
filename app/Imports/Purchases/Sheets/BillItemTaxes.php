<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Purchase\BillItemTax as Request;
use App\Models\Common\Item;
use App\Models\Purchase\Bill;
use App\Models\Purchase\BillItem;
use App\Models\Purchase\BillItemTax as Model;

class BillItemTaxes extends Import
{
    public function model(array $row)
    {
        // @todo remove after laravel-excel 3.2 release
        if ($row['bill_number'] == $this->empty_field) {
            return null;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, 'bill_number')) {
            return [];
        }

        $row = parent::map($row);

        $row['bill_id'] = (int) Bill::number($row['bill_number'])->pluck('id')->first();

        if (empty($row['invoice_item_id']) && !empty($row['item_name'])) {
            $item_id = Item::name($row['item_name'])->pluck('id')->first();
            $row['invoice_item_id'] = BillItem::where('item_id', $item_id)->pluck('id')->first();
        }

        $row['tax_id'] = $this->getTaxId($row);

        if (empty($row['name']) && !empty($row['item_name'])) {
            $row['name'] = $row['item_name'];
        }

        $row['amount'] = (double) $row['amount'];

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
