<?php

namespace App\Imports\Purchases\RecurringBills;

use App\Abstracts\ImportMultipleSheets;
use App\Imports\Purchases\RecurringBills\Sheets\Recurring;
use App\Imports\Purchases\RecurringBills\Sheets\RecurringBills as Base;
use App\Imports\Purchases\RecurringBills\Sheets\RecurringBillItems;
use App\Imports\Purchases\RecurringBills\Sheets\RecurringBillItemTaxes;
use App\Imports\Purchases\RecurringBills\Sheets\RecurringBillHistories;
use App\Imports\Purchases\RecurringBills\Sheets\RecurringBillTotals;

class RecurringBills extends ImportMultipleSheets
{
    public function sheets(): array
    {
        return [
            'recurring_bills' => new Base(),
            'recurring_bill_items' => new RecurringBillItems(),
            'recurring_bill_item_taxes' => new RecurringBillItemTaxes(),
            'recurring_bill_histories' => new RecurringBillHistories(),
            'recurring_bill_totals' => new RecurringBillTotals(),
            'recurring' => new Recurring(),
        ];
    }
}
