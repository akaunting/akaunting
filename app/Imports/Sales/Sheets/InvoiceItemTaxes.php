<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\DocumentItemTax as Request;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use App\Models\Document\DocumentItemTax as Model;

class InvoiceItemTaxes extends Import
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

        $row['document_id'] = (int) Document::invoice()->number($row['invoice_number'])->pluck('id')->first();

        if (empty($row['document_item_id']) && !empty($row['item_name'])) {
            $item_id = Item::name($row['item_name'])->pluck('id')->first();
            $row['document_item_id'] = DocumentItem::invoice()->where('item_id', $item_id)->pluck('id')->first();
        }

        $row['tax_id'] = $this->getTaxId($row);

        if (empty($row['name']) && !empty($row['item_name'])) {
            $row['name'] = $row['item_name'];
        }

        $row['amount'] = (double) $row['amount'];

        $row['type'] = Document::INVOICE_TYPE;

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
