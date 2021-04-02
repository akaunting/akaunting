<?php

namespace App\Exports\Settings;

use App\Abstracts\Export;
use App\Models\Setting\Category as Model;

class Categories extends Export
{
    public function collection()
    {
        $model = Model::usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->cursor();
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
