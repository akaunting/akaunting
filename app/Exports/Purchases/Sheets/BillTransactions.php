<?php

namespace App\Exports\Purchases\Sheets;

use App\Models\Banking\Transaction as Model;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class BillTransactions implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
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
            $model->whereIn('document_id', (array) $this->bill_ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        return [
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
        return 'bill_transactions';
    }
}
