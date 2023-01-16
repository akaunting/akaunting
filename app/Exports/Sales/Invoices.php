<?php

namespace App\Exports\Sales;

use App\Exports\Sales\Sheets\Invoices as Base;
use App\Exports\Sales\Sheets\InvoiceItems;
use App\Exports\Sales\Sheets\InvoiceItemTaxes;
use App\Exports\Sales\Sheets\InvoiceHistories;
use App\Exports\Sales\Sheets\InvoiceTotals;
use App\Exports\Sales\Sheets\InvoiceTransactions;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Invoices implements WithMultipleSheets
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
            new InvoiceItems($this->ids),
            new InvoiceItemTaxes($this->ids),
            new InvoiceHistories($this->ids),
            new InvoiceTotals($this->ids),
            new InvoiceTransactions($this->ids),
        ];
    }
}
