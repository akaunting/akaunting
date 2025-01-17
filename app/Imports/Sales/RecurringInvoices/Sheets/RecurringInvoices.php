<?php

namespace App\Imports\Sales\RecurringInvoices\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Document\Document as Request;
use App\Models\Document\Document as Model;
use App\Traits\Documents;
use Illuminate\Support\Str;

class RecurringInvoices extends Import
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
        $row['type'] = Model::INVOICE_RECURRING_TYPE;
        $row['title'] = $row['title'] ?? Model::INVOICE_RECURRING_TYPE;
        $row['template'] = $row['template'] ?? setting($this->getDocumentSettingKey(Model::INVOICE_RECURRING_TYPE, 'template'), 'default');
        $row['color'] = $row['color'] ?? setting($this->getDocumentSettingKey(Model::INVOICE_RECURRING_TYPE, 'color'), '#55588b');
        $row['contact_country'] = !empty($country) ? $country : null;

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

    //This function is used in import classes. If the data in the row exists in the database, it is returned.
    public function hasRow($row)
    {
        $has_row = $this->model::invoiceRecurring()->get($this->columns)->each(function ($data) {
            $data->setAppends([]);
            $data->unsetRelations();
        });

        $search_value = [];

        //In the model, the fields to be searched for the row are determined.
        foreach ($this->columns as $key) {
            $search_value[$key] = isset($row[$key]) ? $row[$key] : null;
        }

        return in_array($search_value, $has_row->toArray());
    }
}
