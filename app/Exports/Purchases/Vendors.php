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
        $country = null;

        if ($model->country && array_key_exists($model->country, trans('countries'))) {
            $country = trans('countries.' . $model->country);
        }

        $model->country = $country;

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
        ];
    }
}
