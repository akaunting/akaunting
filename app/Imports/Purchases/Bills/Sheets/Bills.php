<?php

namespace App\Imports\Purchases\Bills\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\Document as Request;
use App\Models\Document\Document as Model;
use Illuminate\Support\Str;

class Bills extends Import
{
    public $request_class = Request::class;

    public function model(array $row)
    {
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
