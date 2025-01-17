<?php

namespace App\Imports\Sales\Invoices\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\Document as Request;
use App\Models\Document\Document as Model;
use App\Traits\Documents;
use Illuminate\Support\Str;

class Invoices extends Import
{
    Use Documents;

    public $request_class = Request::class;

    public $model = Model::class;

    public $columns = [
        'type',
        'document_number',
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

        $country = array_search($row['contact_country'], trans('countries'));

        $row['document_number'] = $row['invoice_number'];
        $row['issued_at'] = $row['invoiced_at'];
        $row['category_id'] = $this->getCategoryId($row, 'income');
        $row['contact_id'] = $this->getContactId($row, 'customer');
        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['type'] = Model::INVOICE_TYPE;
        $row['contact_country'] = !empty($country) ? $country : null;
        $row['title'] = $row['title'] ?? Model::INVOICE_TYPE;
        $row['template'] = $row['template'] ?? setting($this->getDocumentSettingKey(Model::INVOICE_TYPE, 'template'), 'default');
        $row['color'] = $row['color'] ?? setting($this->getDocumentSettingKey(Model::INVOICE_TYPE, 'color'), '#55588b');
        $row['parent_id'] = $this->getParentId($row) ?? 0;

        return $row;
    }

    public function prepareRules(array $rules): array
    {
        $rules['invoice_number'] = Str::replaceFirst('unique:documents,NULL', 'unique:documents,document_number', $rules['document_number']);
        $rules['invoiced_at'] = $rules['issued_at'];
        $rules['currency_rate'] = 'required|gt:0';

        unset($rules['document_number'], $rules['issued_at'], $rules['type']);

        return $rules;
    }
}
