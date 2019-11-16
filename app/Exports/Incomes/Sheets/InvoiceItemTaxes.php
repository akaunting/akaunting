<?php

namespace App\Exports\Incomes\Sheets;

use App\Models\Income\InvoiceItemTax as Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class InvoiceItemTaxes implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
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
            $model->invoice_item_id,
            $model->tax_id,
            $model->name,
            $model->amount,
        ];
    }

    public function headings(): array
    {
        return [
            'invoice_id',
            'invoice_item_id',
            'tax_id',
            'name',
            'amount',
        ];
    }

    public function title(): string
    {
        return 'invoice_item_taxes';
    }
}