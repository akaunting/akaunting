<?php

namespace App\Imports\Common\Sheets;

use App\Abstracts\Import;
use App\Events\Common\ModelCreated;
use App\Http\Requests\Common\Item as Request;
use App\Models\Common\Item as Model;

class Items extends Import
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

        $row['category_id'] = $this->getCategoryId($row, 'item');

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
