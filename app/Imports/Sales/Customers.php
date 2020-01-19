<?php

namespace App\Imports\Sales;

use App\Abstracts\Import;
use App\Models\Common\Contact as Model;
use App\Http\Requests\Common\Contact as Request;

class Customers extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row['company_id'] = session('company_id');
        $row['type'] = 'customer';

        // Make enabled field integer
        if (isset($row['enabled'])) {
            $row['enabled'] = (int) $row['enabled'];
        }

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
