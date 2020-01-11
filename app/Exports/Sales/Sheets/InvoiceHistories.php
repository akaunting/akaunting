<?php

namespace App\Exports\Sales\Sheets;

use App\Models\Sale\InvoiceHistory as Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class InvoiceHistories implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
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
            $model->status,
            $model->notify,
            $model->description,
        ];
    }

    public function headings(): array
    {
        return [
            'invoice_id',
            'status',
            'notify',
            'description',
        ];
    }

    public function title(): string
    {
        return 'invoice_histories';
    }
}
