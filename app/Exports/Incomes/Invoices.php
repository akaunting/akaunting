<?php

namespace App\Exports\Incomes;

use App\Exports\Incomes\Sheets\Invoices as Base;
use App\Exports\Incomes\Sheets\InvoiceItems;
use App\Exports\Incomes\Sheets\InvoiceItemTaxes;
use App\Exports\Incomes\Sheets\InvoiceHistories;
use App\Exports\Incomes\Sheets\InvoicePayments;
use App\Exports\Incomes\Sheets\InvoiceTotals;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Invoices implements WithMultipleSheets
{
    public $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function sheets(): array
    {
        return [
            'invoices' => new Base($this->ids),
            'invoice_items' => new InvoiceItems($this->ids),
            'invoice_item_taxes' => new InvoiceItemTaxes($this->ids),
            'invoice_histories' => new InvoiceHistories($this->ids),
            'invoice_payments' => new InvoicePayments($this->ids),
            'invoice_totals' => new InvoiceTotals($this->ids),
        ];
    }
}