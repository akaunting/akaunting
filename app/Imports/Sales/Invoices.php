<?php

namespace App\Imports\Sales;

use App\Imports\Sales\Sheets\Invoices as Base;
use App\Imports\Sales\Sheets\InvoiceItems;
use App\Imports\Sales\Sheets\InvoiceItemTaxes;
use App\Imports\Sales\Sheets\InvoiceHistories;
use App\Imports\Sales\Sheets\InvoicePayments;
use App\Imports\Sales\Sheets\InvoiceTotals;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Invoices implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'invoices' => new Base(),
            'invoice_items' => new InvoiceItems(),
            'invoice_item_taxes' => new InvoiceItemTaxes(),
            'invoice_histories' => new InvoiceHistories(),
            'invoice_payments' => new InvoicePayments(),
            'invoice_totals' => new InvoiceTotals(),
        ];
    }
}