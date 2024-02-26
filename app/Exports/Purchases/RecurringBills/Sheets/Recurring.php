<?php

namespace App\Exports\Purchases\RecurringBills\Sheets;

use App\Abstracts\Export;
use App\Models\Common\Recurring as Model;

class Recurring extends Export
{
    public function collection()
    {
        return Model::bill()->cursor();
    }

    public function map($model): array
    {
        $model->bill_number = $model->recurable->document_number;
                
        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'recurable_type',
            'bill_number',
            'frequency',
            'interval',
            'started_at',
            'limit_date',
            'status',
            'limit_by',
            'limit_count',
            'auto_send',
        ];
    }
}
