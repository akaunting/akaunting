<?php

namespace App\Exports\Banking;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;

class Transactions extends Export
{
    public function collection()
    {
        return Model::usingSearchString(request('search'))->get();
    }

    public function fields(): array
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
}
