<?php

namespace App\Exports\Settings;

use App\Abstracts\Export;
use App\Models\Setting\Tax as Model;

class Taxes extends Export
{
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
