<?php

namespace App\Exports\Common;

use App\Abstracts\Export;
use App\Models\Common\Item as Model;

class Items extends Export
{
    public function collection()
    {
        $model = Model::with(['category', 'tax'])->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $model->category_name = $model->category->name;
        $model->tax_rate = $model->tax->rate;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'name',
            'description',
            'sale_price',
            'purchase_price',
            'category_name',
            'tax_rate',
            'enabled',
        ];
    }
}
