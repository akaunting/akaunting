<?php

namespace App\Exports\Banking\Sheets;

use App\Abstracts\Export;
use App\Models\Common\Recurring as Model;

class Recurring extends Export
{
    public function collection()
    {
        return Model::transaction()->cursor();
    }

    public function map($model): array
    {
        $model->transaction_number = $model->recurable->number;
                
        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'recurable_type',
            'transaction_number',
            'frequency',
            'interval',
            'started_at',
            'status',
            'limit_by',
            'limit_count',
            'auto_send',
        ];
    }
}
