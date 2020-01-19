<?php

namespace App\Imports\Banking;

use App\Abstracts\Import;
use App\Models\Banking\Transaction as Model;
use App\Http\Requests\Banking\Transaction as Request;

class Transactions extends Import
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
