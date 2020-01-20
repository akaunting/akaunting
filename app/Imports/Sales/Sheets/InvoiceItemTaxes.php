<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Sale\InvoiceItemTax as Request;
use App\Models\Common\Item;
use App\Models\Sale\Invoice;
use App\Models\Sale\InvoiceItem;
use App\Models\Sale\InvoiceItemTax as Model;
use App\Models\Setting\Tax;

class InvoiceItemTaxes extends Import
{
    public function model(array $row)
    {
        // @todo remove after 3.2 release
        if ($row['invoice_number'] == $this->empty_field) {
            return null;
        }

        $row['invoice_id'] = Invoice::number($row['invoice_number'])->pluck('id')->first();

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['invoice_id'] = Invoice::number($row['invoice_number'])->pluck('id')->first();

        if (empty($row['invoice_item_id']) && !empty($row['item_name'])) {
            $item_id = Item::name($row['item_name'])->pluck('id')->first();
            $row['invoice_item_id'] = InvoiceItem::where('item_id', $item_id)->pluck('id')->first();
        }

        if (empty($row['tax_id']) && !empty($row['tax_name'])) {
            $row['tax_id'] = Tax::name($row['tax_name'])->pluck('id')->first();
        }

        if (empty($row['tax_id']) && !empty($row['tax_rate'])) {
            $row['tax_id'] = $this->getTaxIdFromRate($row);
        }

        if (empty($row['name']) && !empty($row['item_name'])) {
            $row['name'] = $row['item_name'];
        }

        $row['amount'] = (double) $row['amount'];

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
