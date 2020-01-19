<?php

namespace App\Imports\Purchases;

use App\Imports\Purchases\Sheets\Bills as Base;
use App\Imports\Purchases\Sheets\BillItems;
use App\Imports\Purchases\Sheets\BillItemTaxes;
use App\Imports\Purchases\Sheets\BillHistories;
use App\Imports\Purchases\Sheets\BillTotals;
use App\Imports\Purchases\Sheets\BillTransactions;
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
            'bill_totals' => new BillTotals(),
            'bill_transactions' => new BillTransactions(),
        ];
    }
}
