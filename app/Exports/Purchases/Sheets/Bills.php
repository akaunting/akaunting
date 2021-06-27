<?php

namespace App\Exports\Purchases\Sheets;

use App\Abstracts\Export;
use App\Models\Document\Document as Model;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Bills extends Export implements WithColumnFormatting
{
    public function collection()
    {
        return Model::with('category')->bill()->collectForExport($this->ids, ['document_number' => 'desc']);
    }

    public function map($model): array
    {
        $model->category_name = $model->category->name;
        $model->bill_number = $model->document_number;
        $model->billed_at = $model->issued_at;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'bill_number',
            'order_number',
            'status',
            'billed_at',
            'due_at',
            'amount',
            'currency_code',
            'currency_rate',
            'category_name',
            'contact_name',
            'contact_email',
            'contact_tax_number',
            'contact_phone',
            'contact_address',
            'notes',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
