<?php

namespace App\Imports\Purchases;

use App\Abstracts\Import;
use App\Events\Common\ModelCreated;
use App\Http\Requests\Common\Contact as Request;
use App\Models\Common\Contact as Model;

class Vendors extends Import
{
    public function model(array $row)
    {
        $model = new Model($row);

        event(new ModelCreated($model, $row));

        return $model;
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['type'] = 'vendor';

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
