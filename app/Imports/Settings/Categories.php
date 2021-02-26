<?php

namespace App\Imports\Settings;

use App\Abstracts\Import;
use App\Http\Requests\Setting\Category as Request;
use App\Models\Setting\Category as Model;

class Categories extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
