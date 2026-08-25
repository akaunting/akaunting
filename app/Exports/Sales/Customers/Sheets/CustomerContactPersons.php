<?php

namespace App\Exports\Sales\Customers\Sheets;

use App\Abstracts\Export;
use App\Interfaces\Export\WithParentSheet;
use App\Models\Common\ContactPerson as Model;

class CustomerContactPersons extends Export implements WithParentSheet
{
    public function collection()
    {
        return Model::with('contact')->customer()->collectForExport($this->ids, null, 'contact_id');
    }

    public function map($model): array
    {
        $contact = $model->contact;

        if (empty($contact)) {
            return [];
        }

        $model->customer_name = $contact->name;
        $model->customer_email = $contact->email;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'customer_name',
            'customer_email',
            'name',
            'email',
            'phone',
        ];
    }
}
