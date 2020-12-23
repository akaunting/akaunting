<?php

namespace App\Exports\Document\Sheets;

use App\Abstracts\Export;
use App\Models\Document\Document as Model;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Documents extends Export
{
    public function collection()
    {
        $model = Model::{$this->type}()->with('category')->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $model->category_name = $model->category->name;

        if ($this->type === Model::INVOICE_TYPE) {
            $model->invoice_number = $model->document_number;
            $model->invoiced_at = $model->issued_at;
        } else {
            $model->bill_number = $model->document_number;
            $model->billed_at = $model->issued_at;
        }

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            $this->type === Model::INVOICE_TYPE ? 'invoice_number' : 'bill_number',
            'order_number',
            'status',
            $this->type === Model::INVOICE_TYPE ? 'invoiced_at' : 'billed_at',
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
            'footer',
        ];
    }

    public function title(): string
    {
        return Str::replaceFirst('document', $this->type, parent::title());
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
