<?php

namespace App\Exports\Settings;

use App\Abstracts\Export;
use App\Models\Setting\Category as Model;

class Categories extends Export
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
            'color',
            'enabled',
        ];
    }
}
