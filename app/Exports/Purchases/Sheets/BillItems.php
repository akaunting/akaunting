<?php

namespace App\Exports\Purchases\Sheets;

use App\Abstracts\Export;
use App\Models\Purchase\BillItem as Model;

class BillItems extends Export
{
    public function collection()
    {
        $model = Model::with(['bill', 'item'])->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('bill_id', (array) $this->ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        $model->bill_number = $model->bill->bill_number;
        $model->item_name = $model->item->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'bill_number',
            'item_name',
            'quantity',
            'price',
            'total',
            'tax',
        ];
    }
}
