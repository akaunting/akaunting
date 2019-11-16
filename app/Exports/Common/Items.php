<?php

namespace App\Exports\Common;

use App\Models\Common\Item as Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class Items implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $model = Model::usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        return [
            $model->name,
            $model->description,
            $model->sale_price,
            $model->purchase_price,
            $model->category_id,
            $model->tax_id,
            $model->enabled,
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'description',
            'sale_price',
            'purchase_price',
            'category_id',
            'tax_id',
            'enabled',
        ];
    }

    public function title(): string
    {
        return 'items';
    }
}