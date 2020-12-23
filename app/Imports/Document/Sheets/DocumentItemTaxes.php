<?php

namespace App\Imports\Document\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\DocumentItemTax as Request;
use App\Models\Common\Item;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem;
use App\Models\Document\DocumentItemTax as Model;

class DocumentItemTaxes extends Import
{
    public function model(array $row)
    {
        // @todo remove after laravel-excel 3.2 release
        if ($row[$this->type . '_number'] == $this->empty_field) {
            return null;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, $this->type . '_number')) {
            return [];
        }

        $row = parent::map($row);

        $row['document_id'] = (int) Document::{$this->type}()->number($row[$this->type . '_number'])->pluck('id')->first();

        if (empty($row[$this->type . '_item_id']) && !empty($row['item_name'])) {
            $item_id = Item::name($row['item_name'])->pluck('id')->first();
            $row[$this->type . '_item_id'] = DocumentItem::{$this->type}()->where('item_id', $item_id)->pluck('id')->first();
        }

        $row['tax_id'] = $this->getTaxId($row);

        if (empty($row['name']) && !empty($row['item_name'])) {
            $row['name'] = $row['item_name'];
        }

        $row['amount'] = (double) $row['amount'];

        $row['type'] = $this->type;

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

        unset($rules['invoice_id'], $rules['bill_id']);

        return $rules;
    }
}
