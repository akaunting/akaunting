<?php

namespace App\Exports\Banking;

use App\Models\Banking\Transaction as Model;
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
            $model->account_id,
            $model->paid_at,
            $model->amount,
            $model->currency_code,
            $model->currency_rate,
            $model->document_id,
            $model->contact_id,
            $model->payment_method,
            $model->reconciled,
        ];
    }

    public function headings(): array
    {
        return [
            'type',
            'account_id',
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'document_id',
            'contact_id',
            'payment_method',
            'reconciled',
        ];
    }

    public function title(): string
    {
        return 'transactions';
    }
}