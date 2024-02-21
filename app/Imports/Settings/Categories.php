<?php

namespace App\Imports\Settings;

use App\Abstracts\Import;
use App\Http\Requests\Setting\Category as Request;
use App\Models\Setting\Category as Model;

class Categories extends Import
{
    public $request_class = Request::class;

    public $model = Model::class;

    public $columns = [
        'name',
        'type',
    ];

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

    //This function is used in import classes. If the data in the row exists in the database, it is returned.
    public function hasRow($row)
    {
        $has_row = $this->model::getWithoutChildren($this->columns)->each(function ($data) {
            $data->setAppends([]);
            $data->unsetRelations();
        });

        $search_value = [];

        //In the model, the fields to be searched for the row are determined.
        foreach ($this->columns as $key) {
            $search_value[$key] = isset($row[$key]) ? $row[$key] : null;
        }

        return in_array($search_value, $has_row->toArray());
    }
}
