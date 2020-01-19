<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Models\Sale\InvoiceTotal as Model;
use App\Http\Requests\Sale\InvoiceTotal as Request;

class InvoiceTotals extends Import
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
