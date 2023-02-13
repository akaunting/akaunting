<?php

namespace App\Imports\Common\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Common\ItemTax as Request;
use App\Models\Common\ItemTax as Model;

class ItemTaxes extends Import
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        if ($this->isEmpty($row, 'item_name')) {
            return [];
        }

        $row = parent::map($row);

        $row['item_id'] = $this->getItemId($row);

        $row['tax_id'] = $this->getTaxId($row);

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
