<?php

namespace App\Exports\Banking;

use App\Abstracts\Export;
use App\Models\Banking\Transfer as Model;
use App\Utilities\Date;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class Transfers extends Export implements WithColumnFormatting
{
    public function collection()
    {
        return Model::with(
            'expense_transaction',
            'expense_transaction.account',
            'income_transaction',
            'income_transaction.account'
        )->collectForExport($this->ids);
    }

    public function map($model): array
    {
        $model->transferred_at = Date::parse($model->expense_transaction->paid_at)->format('Y-m-d');
        $model->amount = $model->expense_transaction->amount;
        $model->from_account_name = $model->expense_transaction->account->name;
        $model->from_currency_code = $model->expense_transaction->currency_code;
        $model->from_currency_rate = $model->expense_transaction->currency_rate;
        $model->to_account_name = $model->income_transaction->account->name;
        $model->to_currency_code = $model->income_transaction->currency_code;
        $model->to_currency_rate = $model->income_transaction->currency_rate;
        $model->description = $model->income_transaction->description;
        $model->payment_method = $model->income_transaction->payment_method;
        $model->reference = $model->income_transaction->reference;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'transferred_at',
            'amount',
            'from_currency_code',
            'from_currency_rate',
            'from_account_name',
            'to_currency_code',
            'to_currency_rate',
            'to_account_name',
            'description',
            'payment_method',
            'reference',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
