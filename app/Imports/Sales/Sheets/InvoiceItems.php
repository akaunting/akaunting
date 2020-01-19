<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Models\Sale\InvoiceItem as Model;
use App\Http\Requests\Sale\InvoiceItem as Request;

class InvoiceItems extends Import
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
