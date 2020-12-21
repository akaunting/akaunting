<?php

namespace App\Exports\Common\Sheets;

use App\Abstracts\Export;
use App\Models\Common\ItemTax as Model;

class ItemTaxes extends Export
{
    public function collection()
    {
        $model = Model::with('item', 'tax')->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('item_id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $item = $model->item;

        if (empty($item)) {
            return [];
        }

        $model->item_name = $model->item->name;
        $model->tax_rate = $model->tax->rate;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'item_name',
            'tax_rate',
        ];
    }
}
