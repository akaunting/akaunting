<?php

namespace App\Imports\Common;

use App\Imports\Common\Sheets\Items as Base;
use App\Imports\Common\Sheets\ItemTaxes;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Items implements WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            'items' => new Base(),
            'item_taxes' => new ItemTaxes(),
        ];
    }
}
