<?php

namespace App\Imports\Common;

use App\Abstracts\Import;
use App\Http\Requests\Common\Item as Request;
use App\Models\Common\Item as Model;

class Items extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        if (empty($row['category_id']) && !empty($row['category_name'])) {
            $row['category_id'] = $this->getCategoryIdFromName($row, 'item');
        }

        if (empty($row['tax_id']) && !empty($row['tax_rate'])) {
            $row['tax_id'] = $this->getTaxIdFromRate($row);
        }

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
