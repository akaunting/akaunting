<?php

namespace App\Exports\Purchases\Sheets;

use App\Abstracts\Export;
use App\Models\Purchase\BillItemTax as Model;

class BillItemTaxes extends Export
{
    public function collection()
    {
        $model = Model::with(['bill', 'item', 'tax'])->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('bill_id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $bill = $model->bill;

        if (empty($bill)) {
            return [];
        }

        $model->bill_number = $bill->bill_number;
        $model->item_name = $model->item->name;
        $model->tax_rate = $model->tax->rate;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'bill_number',
            'item_name',
            'tax_rate',
            'amount',
        ];
    }
}
