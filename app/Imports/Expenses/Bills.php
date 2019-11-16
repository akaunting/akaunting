<?php

namespace App\Imports\Expenses;

use App\Imports\Expenses\Sheets\Bills as Base;
use App\Imports\Expenses\Sheets\BillItems;
use App\Imports\Expenses\Sheets\BillItemTaxes;
use App\Imports\Expenses\Sheets\BillHistories;
use App\Imports\Expenses\Sheets\BillPayments;
use App\Imports\Expenses\Sheets\BillTotals;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Bills implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'bills' => new Base(),
            'bill_items' => new BillItems(),
            'bill_item_taxes' => new BillItemTaxes(),
            'bill_histories' => new BillHistories(),
            'bill_payments' => new BillPayments(),
            'bill_totals' => new BillTotals(),
        ];
    }
}