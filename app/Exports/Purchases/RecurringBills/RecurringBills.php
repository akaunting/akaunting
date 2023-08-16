<?php

namespace App\Exports\Purchases\RecurringBills;

use App\Exports\Purchases\RecurringBills\Sheets\Recurring;
use App\Exports\Purchases\RecurringBills\Sheets\RecurringBills as Base;
use App\Exports\Purchases\RecurringBills\Sheets\RecurringBillItems;
use App\Exports\Purchases\RecurringBills\Sheets\RecurringBillItemTaxes;
use App\Exports\Purchases\RecurringBills\Sheets\RecurringBillHistories;
use App\Exports\Purchases\RecurringBills\Sheets\RecurringBillTotals;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RecurringBills implements WithMultipleSheets
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
            new RecurringBillItems($this->ids),
            new RecurringBillItemTaxes($this->ids),
            new RecurringBillHistories($this->ids),
            new RecurringBillTotals($this->ids),
        ];
    }
}
