<?php

namespace App\Imports\Banking\Sheets;

use App\Abstracts\Import;
use App\Models\Banking\Transaction;
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

        $row['recurable_id'] = (int) Transaction::isRecurring()->number($row['transaction_number'])->pluck('id')->first();

        return $row;
    }
}
