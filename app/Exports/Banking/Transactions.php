<?php

namespace App\Exports\Banking;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Transactions extends Export implements WithColumnFormatting
{
    public function collection()
    {
        return Model::with('account', 'category', 'contact', 'document')->collectForExport($this->ids, ['paid_at' => 'desc']);
    }

    public function map($model): array
    {
        $model->account_name = $model->account->name;
        $model->contact_email = $model->contact->email;
        $model->category_name = $model->category->name;
        $model->invoice_bill_number = $model->document->document_number ?? 0;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'type',
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'account_name',
            'invoice_bill_number',
            'contact_email',
            'category_name',
            'description',
            'payment_method',
            'reference',
            'reconciled',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
