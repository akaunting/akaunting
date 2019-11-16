<?php

namespace App\Exports\Expenses;

use App\Exports\Expenses\Sheets\Bills as Base;
use App\Exports\Expenses\Sheets\BillItems;
use App\Exports\Expenses\Sheets\BillItemTaxes;
use App\Exports\Expenses\Sheets\BillHistories;
use App\Exports\Expenses\Sheets\BillPayments;
use App\Exports\Expenses\Sheets\BillTotals;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Bills implements WithMultipleSheets
{
    public $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function sheets(): array
    {
        return [
            'bills' => new Base($this->ids),
            'bill_items' => new BillItems($this->ids),
            'bill_item_taxes' => new BillItemTaxes($this->ids),
            'bill_histories' => new BillHistories($this->ids),
            'bill_payments' => new BillPayments($this->ids),
            'bill_totals' => new BillTotals($this->ids),
        ];
    }
}