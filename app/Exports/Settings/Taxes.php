<?php

namespace App\Exports\Settings;

use App\Abstracts\Export;
use App\Http\Requests\Setting\Tax as Request;
use App\Models\Setting\Tax as Model;

class Taxes extends Export
{
    public $request_class = Request::class;

    public function collection()
    {
        return Model::collectForExport($this->ids);
    }

    public function fields(): array
    {
        return [
            'name',
            'type',
            'rate',
            'enabled',
        ];
    }
}
