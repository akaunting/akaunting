<?php

namespace App\Imports\Purchases\Bills\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\Document as Request;
use App\Models\Document\Document as Model;
use App\Traits\Documents;
use Illuminate\Support\Str;

class Bills extends Import
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
        if ($this->isEmpty($row, 'bill_number')) {
            return [];
        }

        $row['bill_number'] = (string) $row['bill_number'];

        $row = parent::map($row);

        $country = array_search($row['contact_country'], trans('countries'));

        $row['document_number'] = $row['bill_number'];
        $row['issued_at'] = $row['billed_at'];
        $row['category_id'] = $this->getCategoryId($row, 'expense');
        $row['contact_id'] = $this->getContactId($row, 'vendor');
        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['type'] = Model::BILL_TYPE;
        $row['contact_country'] = !empty($country) ? $country : null;
        $row['title'] = $row['title'] ?? Model::BILL_TYPE;
        $row['template'] = $row['template'] ?? setting($this->getDocumentSettingKey(Model::BILL_TYPE, 'template'), 'default');
        $row['color'] = $row['color'] ?? setting($this->getDocumentSettingKey(Model::BILL_TYPE, 'color'), '#55588b');
        $row['parent_id'] = $this->getParentId($row) ?? 0;

        return $row;
    }

    public function prepareRules(array $rules): array
    {
        $rules['bill_number'] = Str::replaceFirst('unique:documents,NULL', 'unique:documents,document_number', $rules['document_number']);
        $rules['billed_at'] = $rules['issued_at'];
        $rules['currency_rate'] = 'required|gt:0';

        unset($rules['document_number'], $rules['issued_at'], $rules['type']);

        return $rules;
    }
}
