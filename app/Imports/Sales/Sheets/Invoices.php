<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Models\Sale\Invoice as Model;
use App\Http\Requests\Sale\Invoice as Request;

class Invoices extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
