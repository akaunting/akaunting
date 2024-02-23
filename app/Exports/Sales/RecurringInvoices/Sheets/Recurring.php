<?php

namespace App\Exports\Sales\RecurringInvoices\Sheets;

use App\Abstracts\Export;
use App\Models\Common\Recurring as Model;

class Recurring extends Export
{
    public function collection()
    {
        return Model::invoice()->cursor();
    }

    public function map($model): array
    {
        $model->invoice_number = $model->recurable->document_number;
                
        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'recurable_type',
            'invoice_number',
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
