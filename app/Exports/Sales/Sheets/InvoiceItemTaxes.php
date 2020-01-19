<?php

namespace App\Exports\Sales\Sheets;

use App\Abstracts\Export;
use App\Models\Sale\InvoiceItemTax as Model;

class InvoiceItemTaxes extends Export
{
    public function collection()
    {
        $model = Model::usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('invoice_id', (array) $this->ids);
        }

        return $model->get();
    }

    public function fields(): array
    {
        return [
            'invoice_id',
            'invoice_item_id',
            'tax_id',
            'name',
            'amount',
        ];
    }
}
