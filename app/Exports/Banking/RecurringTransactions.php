<?php

namespace App\Exports\Banking;

use App\Exports\Banking\Sheets\Recurring;
use App\Exports\Banking\Sheets\RecurringTransactions as Base;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RecurringTransactions implements WithMultipleSheets
{
    use Exportable;

    public $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function sheets(): array
    {
        return [
            new Recurring($this->ids),
            new Base($this->ids),
        ];
    }
}
