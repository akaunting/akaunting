<?php

namespace App\Exports\Sales;

use App\Abstracts\Export;
use App\Models\Common\Contact as Model;

class Customers extends Export
{
    public function collection()
    {
        return Model::customer()->collectForExport($this->ids);
    }

    public function map($model): array
    {
        $country = null;

        if ($model->country && array_key_exists($model->country, trans('countries'))) {
            $country = trans('countries.' . $model->country);
        }

        $model->country = $country;

        $model->can_login = $model->user_id ? true : false;

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
            'can_login',
        ];
    }
}
