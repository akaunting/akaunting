<?php

namespace App\Exports\Sales\Customers;

use App\Exports\Sales\Customers\Sheets\Customers as Base;
use App\Exports\Sales\Customers\Sheets\CustomerContactPersons;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Customers implements WithMultipleSheets
{
    use Exportable;

    public $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function sheets(): array
    {
        return [
            new Base($this->ids),
            new CustomerContactPersons($this->ids),
        ];
    }
}
