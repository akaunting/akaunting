<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\Document as Request;
use App\Models\Document\Document as Model;
use Illuminate\Support\Str;

class Invoices extends Import
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

        $row['document_number'] = $row['invoice_number'];
        $row['issued_at'] = $row['invoiced_at'];
        $row['category_id'] = $this->getCategoryId($row, 'income');
        $row['contact_id'] = $this->getContactId($row, 'customer');
        $row['type'] = Model::INVOICE_TYPE;

        return $row;
    }

    public function rules(): array
    {
        $rules = (new Request())->rules();

        $rules['invoice_number'] = Str::replaceFirst('unique:documents,NULL', 'unique:documents,document_number', $rules['document_number']);
        $rules['invoiced_at'] = $rules['issued_at'];
        $rules['currency_rate'] = 'required';

        unset($rules['document_number'], $rules['issued_at'], $rules['type']);

        return $this->replaceForBatchRules($rules);
    }
}
