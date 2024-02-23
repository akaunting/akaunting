<?php

namespace App\Imports\Sales\Invoices\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\DocumentItem as Request;
use App\Models\Document\Document;
use App\Models\Document\DocumentItem as Model;

class InvoiceItems extends Import
{
    public $request_class = Request::class;

    public $model = Model::class;

    public $columns = [
        'type',
        'document_id',
        'item_id',
        'name',
        'description',
        'quantity',
        'price',
        'tax',
        'discount_rate',
        'total'
    ];

    public function model(array $row)
    {
        if (self::hasRow($row)) {
            return;
        }
        
        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, 'invoice_number')) {
            return [];
        }

        $row['invoice_number'] = (string) $row['invoice_number'];

        $row = parent::map($row);

        $row['document_id'] = (int) Document::invoice()->number($row['invoice_number'])->pluck('id')->first();

        if (empty($row['item_id']) && ! empty($row['item_name'])) {
            $row['item_id'] = $this->getItemIdFromName($row);

            $row['name'] = $row['item_name'];
        }

        $row['description'] = !empty($row['item_description']) ? $row['item_description'] : '';

        $row['tax'] = (double) $row['tax'];
        $row['tax_id'] = 0;
        $row['type'] = Document::INVOICE_TYPE;

        return $row;
    }

    public function prepareRules(array $rules): array
    {
        $rules['invoice_number'] = 'required|string';

        unset($rules['invoice_id']);

        return $rules;
    }
}
