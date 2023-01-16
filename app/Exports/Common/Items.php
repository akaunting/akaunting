<?php

namespace App\Exports\Common;

use App\Exports\Common\Sheets\Items as Base;
use App\Exports\Common\Sheets\ItemTaxes;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Items implements WithMultipleSheets
{
    use Exportable;

    public $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function sheets(): array
    {
        return [
            new Base($this->ids),
            new ItemTaxes($this->ids),
        ];
    }
}
