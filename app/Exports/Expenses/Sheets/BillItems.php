<?php

namespace App\Exports\Expenses\Sheets;

use App\Models\Expense\BillItem as Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BillItems implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public $bill_ids;

    public function __construct($bill_ids = null)
    {
        $this->bill_ids = $bill_ids;
    }

    public function collection()
    {
        $model = Model::usingSearchString(request('search'));

        if (!empty($this->bill_ids)) {
            $model->whereIn('bill_id', (array) $this->bill_ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        return [
            $model->bill_id,
            $model->item_id,
            $model->name,
            $model->price,
            $model->total,
            $model->tax,
        ];
    }

    public function headings(): array
    {
        return [
            'bill_id',
            'item_id',
            'name',
            'price',
            'total',
            'tax',
        ];
    }

    public function title(): string
    {
        return 'bill_items';
    }
}