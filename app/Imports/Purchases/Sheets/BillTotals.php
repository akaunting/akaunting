<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Models\Purchase\BillTotal as Model;
use App\Http\Requests\Purchase\BillTotal as Request;

class BillTotals extends Import
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
