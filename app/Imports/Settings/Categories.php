<?php

namespace App\Imports\Settings;

use App\Abstracts\Import;
use App\Events\Common\ModelCreated;
use App\Http\Requests\Setting\Category as Request;
use App\Models\Setting\Category as Model;

class Categories extends Import
{
    public function model(array $row)
    {
        $model = new Model($row);

        event(new ModelCreated($model, $row));

        return $model;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
