<?php

namespace App\Imports\Sales\RecurringInvoices\Sheets;

use App\Abstracts\Import;
use App\Models\Document\Document;
use App\Models\Common\Recurring as Model;

class Recurring extends Import
{
    public $model = Model::class;

    public $columns = [
        'recurable_type',
        'recurable_id',
        'started_at',
        'limit_date',
    ];
    
    public function model(array $row)
    {
        if (self::hasRow($row)) {
            return;
        }
        
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
