<?php

namespace App\Imports\Sales\Sheets;

use App\Abstracts\Import;
use App\Models\Banking\Transaction as Model;
use App\Http\Requests\Banking\Transaction as Request;

class InvoiceTranactions extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row['company_id'] = session('company_id');
        $row['type'] = 'income';

        // Make reconciled field integer
        if (isset($row['reconciled'])) {
            $row['reconciled'] = (int) $row['reconciled'];
        }

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
