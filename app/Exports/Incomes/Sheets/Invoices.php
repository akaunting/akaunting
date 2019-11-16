<?php

namespace App\Exports\Incomes\Sheets;

use App\Models\Income\Invoice as Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class Invoices implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
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
            $model->invoice_number,
            $model->order_number,
            $model->invoice_status_code,
            $model->invoiced_at,
            $model->due_at,
            $model->amount,
            $model->currency_code,
            $model->currency_rate,
            $model->contact_id,
            $model->contact_name,
            $model->contact_email,
            $model->contact_tax_number,
            $model->contact_phone,
            $model->contact_address,
            $model->notes,
            $model->category_id,
            $model->footer,
        ];
    }

    public function headings(): array
    {
        return [
            'invoice_number',
            'order_number',
            'invoice_status_code',
            'invoiced_at',
            'due_at',
            'amount',
            'currency_code',
            'currency_rate',
            'contact_id',
            'contact_name',
            'contact_email',
            'contact_tax_number',
            'contact_address',
            'notes',
            'category_id',
            'footer',
        ];
    }

    public function title(): string
    {
        return 'invoices';
    }
}