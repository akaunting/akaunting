<?php

namespace App\Imports\Sales;

use App\Abstracts\Import;
use App\Http\Requests\Common\Contact as Request;
use App\Models\Common\Contact as Model;

class Customers extends Import
{
    public $request_class = Request::class;

    public $model = Model::class;

    public $columns = [
        'type',
        'name',
        'email',
    ];

    public function model(array $row)
    {
        if (self::hasRow($row)) {
            return;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $country = array_search($row['country'], trans('countries'));

        $row['type'] = 'customer';
        $row['country'] = !empty($country) ? $country : null;
        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['user_id'] = null;

        if (isset($row['can_login']) && isset($row['email'])) {
            $row['user_id'] = user_model_class()::where('email', $row['email'])->first()?->id ?? null;
        }

        return $row;
    }
}
