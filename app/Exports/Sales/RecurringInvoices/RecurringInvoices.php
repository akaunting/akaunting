<?php

namespace App\Exports\Sales\RecurringInvoices;

use App\Exports\Sales\RecurringInvoices\Sheets\Recurring;
use App\Exports\Sales\RecurringInvoices\Sheets\RecurringInvoices as Base;
use App\Exports\Sales\RecurringInvoices\Sheets\RecurringInvoiceItems;
use App\Exports\Sales\RecurringInvoices\Sheets\RecurringInvoiceItemTaxes;
use App\Exports\Sales\RecurringInvoices\Sheets\RecurringInvoiceHistories;
use App\Exports\Sales\RecurringInvoices\Sheets\RecurringInvoiceTotals;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RecurringInvoices implements WithMultipleSheets
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
            new Recurring($this->ids),
            new Base($this->ids),
            new RecurringInvoiceItems($this->ids),
            new RecurringInvoiceItemTaxes($this->ids),
            new RecurringInvoiceHistories($this->ids),
            new RecurringInvoiceTotals($this->ids),
        ];
    }
}
