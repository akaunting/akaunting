<?php

namespace App\Exports\Sales\Sheets;

use App\Models\Sale\Invoice as Model;
use Jenssegers\Date\Date;
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
            $model->status,
            Date::parse($model->invoiced_at)->format('Y-m-d'),
            Date::parse($model->due_at)->format('Y-m-d'),
            $model->amount,
            $model->currency_code,
            $model->currency_rate,
            $model->category_id,
            $model->contact_id,
            $model->contact_name,
            $model->contact_email,
            $model->contact_tax_number,
            $model->contact_phone,
            $model->contact_address,
            $model->notes,
            $model->footer,
        ];
    }

    public function headings(): array
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
            'category_id',
            'contact_id',
            'contact_name',
            'contact_email',
            'contact_tax_number',
            'contact_phone',
            'contact_address',
            'notes',
            'footer',
        ];
    }

    public function title(): string
    {
        return 'invoices';
    }
}
