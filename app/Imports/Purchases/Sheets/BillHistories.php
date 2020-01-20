<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Models\Purchase\BillHistory as Model;
use App\Http\Requests\Purchase\BillHistory as Request;

class BillHistories extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['notify'] = (int) $row['notify'];

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
