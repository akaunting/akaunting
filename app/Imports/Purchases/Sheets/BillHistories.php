<?php

namespace App\Imports\Purchases\Sheets;

use App\Abstracts\Import;
use App\Http\Requests\Purchase\BillHistory as Request;
use App\Models\Purchase\Bill;
use App\Models\Purchase\BillHistory as Model;

class BillHistories extends Import
{
    public function model(array $row)
    {
        // @todo remove after 3.2 release
        if ($row['bill_number'] == $this->empty_field) {
            return null;
        }

        return new Model($row);
    }

    public function map($row): array
    {
        $row = parent::map($row);

        $row['bill_id'] = Bill::number($row['bill_number'])->pluck('id')->first();

        $row['notify'] = (int) $row['notify'];

        return $row;
    }

    public function rules(): array
    {
        $rules = (new Request())->rules();

        $rules['bill_number'] = 'required|string';
        unset($rules['bill_id']);

        return $rules;
    }
}
