<?php

namespace App\Imports\Common;

use App\Abstracts\ImportMultipleSheets;
use App\Imports\Common\Sheets\Items as Base;
use App\Imports\Common\Sheets\ItemTaxes;

class Items extends ImportMultipleSheets
{
    public function sheets(): array
    {
        return [
            'items' => new Base(),
            'item_taxes' => new ItemTaxes(),
        ];
    }
}
