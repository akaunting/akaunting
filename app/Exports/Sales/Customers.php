<?php

namespace App\Exports\Sales;

use App\Abstracts\Export;
use App\Models\Common\Contact as Model;

class Customers extends Export
{
    public function collection()
    {
        $model = Model::type('customer')->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function fields(): array
    {
        return [
            'name',
            'email',
            'tax_number',
            'phone',
            'address',
            'website',
            'currency_code',
            'reference',
            'enabled',
            'user_id',
        ];
    }
}
