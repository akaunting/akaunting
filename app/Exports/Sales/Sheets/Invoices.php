<?php

namespace App\Exports\Sales\Sheets;

use App\Abstracts\Export;
use App\Models\Sale\Invoice as Model;

class Invoices extends Export
{
    public function collection()
    {
        $model = Model::with(['category'])->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $model->category_name = $model->category->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'invoice_number',
            'order_number',
            'status',
            'invoiced_at',
            'due_at',
            'amount',
            'currency_code',
            'currency_rate',
            'category_name',
            'contact_name',
            'contact_email',
            'contact_tax_number',
            'contact_phone',
            'contact_address',
            'notes',
            'footer',
        ];
    }
}
