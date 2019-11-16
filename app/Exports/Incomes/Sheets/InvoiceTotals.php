<?php

namespace App\Exports\Incomes\Sheets;

use App\Models\Income\InvoiceTotal as Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class InvoiceTotals implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public $invoice_ids;

    public function __construct($invoice_ids = null)
    {
        $this->invoice_ids = $invoice_ids;
    }

    public function collection()
    {
        $model = Model::usingSearchString(request('search'));

        if (!empty($this->invoice_ids)) {
            $model->whereIn('invoice_id', (array) $this->invoice_ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        return [
            $model->invoice_id,
            $model->code,
            $model->name,
            $model->amount,
            $model->sort_order,
        ];
    }

    public function headings(): array
    {
        return [
            'invoice_id',
            'code',
            'name',
            'amount',
            'sort_order',
        ];
    }

    public function title(): string
    {
        return 'invoice_totals';
    }
}