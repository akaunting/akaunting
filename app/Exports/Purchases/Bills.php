<?php

namespace App\Exports\Purchases;

use App\Exports\Purchases\Sheets\Bills as Base;
use App\Exports\Purchases\Sheets\BillItems;
use App\Exports\Purchases\Sheets\BillItemTaxes;
use App\Exports\Purchases\Sheets\BillHistories;
use App\Exports\Purchases\Sheets\BillTotals;
use App\Exports\Purchases\Sheets\BillTransactions;
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
            'bill_totals' => new BillTotals($this->ids),
            'bill_transactions' => new BillTransactions($this->ids),
        ];
    }
}
