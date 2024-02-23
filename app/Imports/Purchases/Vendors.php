<?php

namespace App\Imports\Purchases;

use App\Abstracts\Import;
use App\Http\Requests\Common\Contact as Request;
use App\Models\Common\Contact as Model;

class Vendors extends Import
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

        $row['type'] = 'vendor';
        $row['country'] = !empty($country) ? $country : null;
        $row['currency_code'] = $this->getCurrencyCode($row);
        $row['user_id'] = null;

        return $row;
    }
}
