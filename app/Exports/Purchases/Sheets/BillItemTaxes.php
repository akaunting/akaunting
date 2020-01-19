<?php

namespace App\Exports\Purchases\Sheets;

use App\Abstracts\Export;
use App\Models\Purchase\BillItemTax as Model;

class BillItemTaxes extends Export
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
            'bill_item_id',
            'tax_id',
            'name',
            'amount',
        ];
    }
}
