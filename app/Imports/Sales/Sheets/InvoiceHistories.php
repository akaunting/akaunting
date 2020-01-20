<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Models\Sale\InvoiceHistory as Model;
use App\Http\Requests\Sale\InvoiceHistory as Request;

class InvoiceHistories extends Import
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
