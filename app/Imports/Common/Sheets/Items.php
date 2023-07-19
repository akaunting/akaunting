<?php

namespace App\Imports\Common\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Common\Item as Request;
use App\Models\Common\Item as Model;

class Items extends Import
{
    public $request_class = Request::class;

    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['sale_information'] = isset($row['sale_price']) ?? false;

        $row['purchase_information'] = isset($row['purchase_price']) ?? false;

        $row['category_id'] = $this->getCategoryId($row, 'item');

        return $row;
    }
}
