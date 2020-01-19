<?php

namespace App\Exports\Banking;

use App\Models\Banking\Transaction as Model;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class Transactions implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public function collection()
    {
        return Model::usingSearchString(request('search'))->get();
    }

    public function map($model): array
    {
        return [
            $model->type,
            Date::parse($model->paid_at)->format('Y-m-d'),
            $model->amount,
            $model->currency_code,
            $model->currency_rate,
            $model->account_id,
            $model->document_id,
            $model->contact_id,
            $model->category_id,
            $model->description,
            $model->payment_method,
            $model->reference,
            $model->reconciled,
        ];
    }

    public function headings(): array
    {
        return [
            'type',
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'account_id',
            'document_id',
            'contact_id',
            'category_id',
            'description',
            'payment_method',
            'reference',
            'reconciled',
        ];
    }

    public function title(): string
    {
        return 'transactions';
    }
}
