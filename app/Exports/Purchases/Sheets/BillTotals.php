<?php

namespace App\Exports\Purchases\Sheets;

use App\Abstracts\Export;
use App\Models\Purchase\BillTotal as Model;

class BillTotals extends Export
{
    public function collection()
    {
        $model = Model::with(['bill'])->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('bill_id', (array) $this->ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        $model->bill_number = $model->bill->bill_number;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'bill_number',
            'code',
            'name',
            'amount',
            'sort_order',
        ];
    }
}
