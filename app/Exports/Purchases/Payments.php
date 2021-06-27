<?php

namespace App\Exports\Purchases;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Payments extends Export implements WithColumnFormatting
{
    public function collection()
    {
        return Model::with('account', 'bill', 'category', 'contact')->expense()->collectForExport($this->ids, ['paid_at' => 'desc']);
    }

    public function map($model): array
    {
        $model->account_name = $model->account->name;
        $model->bill_number = $model->bill->document_number ?? 0;
        $model->contact_email = $model->contact->email;
        $model->category_name = $model->category->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'account_name',
            'bill_number',
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
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
