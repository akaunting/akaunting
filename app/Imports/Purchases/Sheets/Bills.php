<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Models\Purchase\Bill as Model;
use App\Http\Requests\Purchase\Bill as Request;
use Jenssegers\Date\Date;

class Bills extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row['company_id'] = session('company_id');
        $row['billed_at'] = Date::parse($row['billed_at'])->format('Y-m-d H:i:s');
        $row['due_at'] = Date::parse($row['due_at'])->format('Y-m-d H:i:s');

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
