<?php

namespace App\Imports\Banking;

use App\Abstracts\ImportMultipleSheets;
use App\Imports\Banking\Sheets\Recurring;
use App\Imports\Banking\Sheets\RecurringTransactions as Base;

class RecurringTransactions extends ImportMultipleSheets
{
    public function sheets(): array
    {
        return [
            'recurring_transactions' => new Base(),
            'recurring' => new Recurring(),
        ];
    }
}
