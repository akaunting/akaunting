<?php

namespace App\Imports\Sales\Customers\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Common\ContactPerson as Request;
use App\Models\Common\Contact;
use App\Models\Common\ContactPerson as Model;

class CustomerContactPersons extends Import
{
    public $request_class = Request::class;

    public $model = Model::class;

    public $columns = [
        'type',
        'contact_id',
        'name',
        'email',
        'phone',
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
        if ($this->isEmpty($row, 'customer_name')) {
            return [];
        }

        $row['customer_name'] = (string) $row['customer_name'];

        $row = parent::map($row);

        // Numeric looking cells are read as numbers, but the columns are strings.
        $row['name'] = !empty($row['name']) ? (string) $row['name'] : null;
        $row['phone'] = !empty($row['phone']) ? (string) $row['phone'] : null;

        // getContactId() looks for the parent contact by the contact_* columns.
        $row['contact_name'] = $row['customer_name'];
        $row['contact_email'] = !empty($row['customer_email']) ? $row['customer_email'] : null;

        $row['type'] = Contact::CUSTOMER_TYPE;
        $row['contact_id'] = $this->getContactId($row, Contact::CUSTOMER_TYPE);

        return $row;
    }

    public function prepareRules(array $rules): array
    {
        $rules['customer_name'] = 'required|string';

        return $rules;
    }
}
