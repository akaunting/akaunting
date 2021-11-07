<?php

namespace App\Imports\Sales;

use App\Abstracts\Import;
use App\Http\Requests\Common\Contact as Request;
use App\Models\Common\Contact as Model;

class Customers extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $country = array_search($row['country'], trans('countries'));

        $row['type'] = 'customer';
        $row['country'] = !empty($country) ? $country : null;

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
