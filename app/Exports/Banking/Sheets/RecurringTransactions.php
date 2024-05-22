<?php

namespace App\Exports\Banking\Sheets;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;
use App\Interfaces\Export\WithParentSheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RecurringTransactions extends Export implements WithColumnFormatting, WithParentSheet
{
    public function collection()
    {
        return Model::with('account', 'category', 'contact', 'document')->isRecurring()->cursor();
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
            'number',
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
            'C' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
