<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Models\Purchase\Bill as Model;
use App\Http\Requests\Purchase\Bill as Request;

class Bills extends Import
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
