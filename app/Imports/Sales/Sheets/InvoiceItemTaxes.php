<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Models\Sale\InvoiceItemTax as Model;

class InvoiceItemTaxes extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }
}
