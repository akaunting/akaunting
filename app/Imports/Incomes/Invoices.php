<?php

namespace App\Imports\Incomes;

use App\Imports\Incomes\Sheets\Invoices as Base;
use App\Imports\Incomes\Sheets\InvoiceItems;
use App\Imports\Incomes\Sheets\InvoiceItemTaxes;
use App\Imports\Incomes\Sheets\InvoiceHistories;
use App\Imports\Incomes\Sheets\InvoicePayments;
use App\Imports\Incomes\Sheets\InvoiceTotals;
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