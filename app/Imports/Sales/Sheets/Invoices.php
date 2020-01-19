<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Models\Sale\Invoice as Model;
use App\Http\Requests\Sale\Invoice as Request;
use Jenssegers\Date\Date;

class Invoices extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row['company_id'] = session('company_id');
        $row['invoiced_at'] = Date::parse($row['invoiced_at'])->format('Y-m-d H:i:s');
        $row['due_at'] = Date::parse($row['due_at'])->format('Y-m-d H:i:s');

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
