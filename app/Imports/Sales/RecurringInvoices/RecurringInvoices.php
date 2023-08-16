<?php

namespace App\Imports\Sales\RecurringInvoices;

use App\Abstracts\ImportMultipleSheets;
use App\Imports\Sales\RecurringInvoices\Sheets\Recurring;
use App\Imports\Sales\RecurringInvoices\Sheets\RecurringInvoices as Base;
use App\Imports\Sales\RecurringInvoices\Sheets\RecurringInvoiceItems;
use App\Imports\Sales\RecurringInvoices\Sheets\RecurringInvoiceItemTaxes;
use App\Imports\Sales\RecurringInvoices\Sheets\RecurringInvoiceHistories;
use App\Imports\Sales\RecurringInvoices\Sheets\RecurringInvoiceTotals;

class RecurringInvoices extends ImportMultipleSheets
{
    public function sheets(): array
    {
        return [
            'recurring_invoices' => new Base(),
            'recurring_invoice_items' => new RecurringInvoiceItems(),
            'recurring_invoice_item_taxes' => new RecurringInvoiceItemTaxes(),
            'recurring_invoice_histories' => new RecurringInvoiceHistories(),
            'recurring_invoice_totals' => new RecurringInvoiceTotals(),
            'recurring' => new Recurring(),
        ];
    }
}
