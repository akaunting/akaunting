<?php

namespace App\Exports\Expenses\Sheets;

use App\Models\Expense\BillTotal as Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BillTotals implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
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
            $model->code,
            $model->name,
            $model->amount,
            $model->sort_order,
        ];
    }

    public function headings(): array
    {
        return [
            'bill_id',
            'code',
            'name',
            'amount',
            'sort_order',
        ];
    }

    public function title(): string
    {
        return 'bill_totals';
    }
}