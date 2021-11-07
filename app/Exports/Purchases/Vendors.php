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

    public function map($model): array
    {
        $model->country = ($model->country) ? trans('countries.' . $model->country) : null;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'name',
            'email',
            'tax_number',
            'phone',
            'address',
            'country',
            'state',
            'zip_code',
            'city',
            'website',
            'currency_code',
            'reference',
            'enabled',
            'user_id',
        ];
    }
}
