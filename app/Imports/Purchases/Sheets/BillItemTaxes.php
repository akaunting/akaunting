<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Models\Purchase\BillItemTax as Model;

class BillItemTaxes extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }
}
