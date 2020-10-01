<?php

namespace App\Exports\Banking;

use App\Abstracts\Export;
use App\Models\Banking\Transfer as Model;
use App\Utilities\Date;

class Transfers extends Export
{
    public function collection()
    {
        $model = Model::with(
            'expense_transaction',
            'expense_transaction.account',
            'income_transaction',
            'income_transaction.account'
        )->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->cursor();
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
}
