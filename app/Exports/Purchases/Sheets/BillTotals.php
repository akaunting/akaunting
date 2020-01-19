<?php

namespace App\Exports\Purchases\Sheets;

use App\Abstracts\Export;
use App\Models\Purchase\BillTotal as Model;

class BillTotals extends Export
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
            'code',
            'name',
            'amount',
            'sort_order',
        ];
    }
}
