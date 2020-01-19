<?php

namespace App\Exports\Purchases\Sheets;

use App\Abstracts\Export;
use App\Models\Purchase\BillHistory as Model;

class BillHistories extends Export
{
    public function collection()
    {
        $model = Model::usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('bill_id', (array) $this->ids);
        }

        return $model->get();
    }

    public function fields(): array
    {
        return [
            'bill_id',
            'status',
            'notify',
            'description',
        ];
    }
}
