<?php

namespace App\Exports\Expenses\Sheets;

use App\Models\Banking\Transaction as Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BillPayments implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public $bill_ids;

    public function __construct($bill_ids = null)
    {
        $this->bill_ids = $bill_ids;
    }

    public function collection()
    {
        $model = Model::type('expense')->isDocument()->usingSearchString(request('search'));

        if (!empty($this->bill_ids)) {
            $model->whereIn('bill_id', (array) $this->bill_ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        return [
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
        return 'bill_payments';
    }
}