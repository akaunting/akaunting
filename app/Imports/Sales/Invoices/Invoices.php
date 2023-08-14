<?php

namespace App\Imports\Sales\Invoices;

use App\Abstracts\ImportMultipleSheets;
use App\Imports\Sales\Invoices\Sheets\Invoices as Base;
use App\Imports\Sales\Invoices\Sheets\InvoiceItems;
use App\Imports\Sales\Invoices\Sheets\InvoiceItemTaxes;
use App\Imports\Sales\Invoices\Sheets\InvoiceHistories;
use App\Imports\Sales\Invoices\Sheets\InvoiceTotals;
use App\Imports\Sales\Invoices\Sheets\InvoiceTransactions;

class Invoices extends ImportMultipleSheets
{
    public function sheets(): array
    {
        return [
            'invoices' => new Base(),
            'invoice_items' => new InvoiceItems(),
            'invoice_item_taxes' => new InvoiceItemTaxes(),
            'invoice_histories' => new InvoiceHistories(),
            'invoice_totals' => new InvoiceTotals(),
            'invoice_transactions' => new InvoiceTransactions(),
        ];
    }
}
