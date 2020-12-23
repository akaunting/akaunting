<?php

namespace App\Imports\Document\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\Document as Request;
use App\Models\Document\Document as Model;
use Illuminate\Support\Str;

class Documents extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, $this->type . '_number')) {
            return [];
        }

        $row = parent::map($row);

        if ($this->type === Model::INVOICE_TYPE) {
            $row['document_number'] = $row['invoice_number'];
            $row['issued_at'] = $row['invoiced_at'];
        } else {
            $row['document_number'] = $row['bill_number'];
            $row['issued_at'] = $row['billed_at'];
        }

        $row['category_id'] = $this->getCategoryId($row, $this->type === Model::INVOICE_TYPE ? 'income' : 'expense');
        $row['contact_id'] = $this->getContactId($row, $this->type === Model::INVOICE_TYPE ? 'customer' : 'vendor');
        $row['type'] = $this->type;

        return $row;
    }

    public function rules(): array
    {
        $rules = (new Request())->rules();

        if ($this->type === Model::INVOICE_TYPE) {
            $rules['invoice_number'] = Str::replaceFirst('unique:documents,NULL', 'unique:documents,document_number', $rules['document_number']);
            $rules['invoiced_at'] = $rules['issued_at'];
        } else {
            $rules['bill_number'] = Str::replaceFirst('unique:documents,NULL', 'unique:documents,document_number', $rules['document_number']);
            $rules['billed_at'] = $rules['issued_at'];
        }

        unset($rules['document_number'], $rules['issued_at'], $rules['type']);

        return $rules;
    }
}
