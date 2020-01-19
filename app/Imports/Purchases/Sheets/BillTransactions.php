<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Models\Banking\Transaction as Model;
use App\Http\Requests\Banking\Transaction as Request;
use Jenssegers\Date\Date;

class BillTransactions extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row['company_id'] = session('company_id');
        $row['type'] = 'expense';
        $row['paid_at'] = Date::parse($row['paid_at'])->format('Y-m-d H:i:s');

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
