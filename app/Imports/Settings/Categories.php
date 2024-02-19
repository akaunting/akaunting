<?php

namespace App\Imports\Settings;

use App\Abstracts\Import;
use App\Http\Requests\Setting\Category as Request;
use App\Models\Setting\Category as Model;

class Categories extends Import
{
    public $request_class = Request::class;

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

        $row['type'] = $this->getCategoryType($row['type']);
        $row['parent_id'] = $this->getParentId($row) ?? null;

        return $row;
    }
}
