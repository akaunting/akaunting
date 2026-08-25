<?php

namespace App\Imports\Sales\Customers;

use App\Abstracts\ImportMultipleSheets;
use App\Imports\Sales\Customers\Sheets\Customers as Base;
use App\Imports\Sales\Customers\Sheets\CustomerContactPersons;

class Customers extends ImportMultipleSheets
{
    public function sheets(): array
    {
        return [
            'customers' => new Base(),
            'customer_contact_persons' => new CustomerContactPersons(),
        ];
    }
}
