<?php

namespace App\Imports\Sales\RecurringInvoices\Sheets;

use App\Models\Document\Document;
use App\Models\Common\Recurring as Model;
use Modules\BackupRestore\Abstracts\Import;

class Recurring extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['recurable_id'] = (int) Document::where('type', '=', Document::INVOICE_RECURRING_TYPE)
            ->number($row['invoice_number'])
            ->pluck('id')
            ->first();

        return $row;
    }
}
