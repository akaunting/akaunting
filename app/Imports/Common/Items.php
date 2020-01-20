<?php

namespace App\Imports\Common;

use App\Abstracts\Import;
use App\Http\Requests\Common\Item as Request;
use App\Models\Common\Item as Model;
use App\Models\Setting\Category;
use App\Models\Setting\Tax;

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
            $row['category_id'] = Category::firstOrCreate([
                'name'              => $row['category_name'],
            ], [
                'company_id'        => session('company_id'),
                'type'              => 'income',
                'color'             => '#' . dechex(rand(0x000000, 0xFFFFFF)),
                'enabled'           => 1,
            ])->id;
        }

        if (empty($row['tax_id']) && !empty($row['tax_rate'])) {
            $row['tax_id'] = Tax::firstOrCreate([
                'rate'          => $row['tax_rate'],
            ], [
                'company_id'    => session('company_id'),
                'type'          => 'normal',
                'name'          => $row['tax_rate'],
                'enabled'       => 1,
            ])->id;
        }

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }
}
