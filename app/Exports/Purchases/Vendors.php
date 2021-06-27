<?php

namespace App\Exports\Purchases;

use App\Abstracts\Export;
use App\Models\Common\Contact as Model;

class Vendors extends Export
{
    public function collection()
    {
        return Model::vendor()->collectForExport($this->ids);
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
