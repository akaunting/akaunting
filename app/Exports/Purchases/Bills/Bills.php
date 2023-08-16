<?php

namespace App\Exports\Purchases\Bills;

use App\Exports\Purchases\Bills\Sheets\Bills as Base;
use App\Exports\Purchases\Bills\Sheets\BillItems;
use App\Exports\Purchases\Bills\Sheets\BillItemTaxes;
use App\Exports\Purchases\Bills\Sheets\BillHistories;
use App\Exports\Purchases\Bills\Sheets\BillTotals;
use App\Exports\Purchases\Bills\Sheets\BillTransactions;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Bills implements WithMultipleSheets
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
            new Base($this->ids),
            new BillItems($this->ids),
            new BillItemTaxes($this->ids),
            new BillHistories($this->ids),
            new BillTotals($this->ids),
            new BillTransactions($this->ids),
        ];
    }
}
