<?php

namespace App\Exports\Document;

use App\Exports\Document\Sheets\Documents as Base;
use App\Exports\Document\Sheets\DocumentItems;
use App\Exports\Document\Sheets\DocumentItemTaxes;
use App\Exports\Document\Sheets\DocumentHistories;
use App\Exports\Document\Sheets\DocumentTotals;
use App\Exports\Document\Sheets\DocumentTransactions;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Documents implements WithMultipleSheets
{
    public $ids;

    /**
     * @var string
     */
    protected $type;

    public function __construct($ids = null, string $type = '')
    {
        $this->ids = $ids;
        $this->type = $type;
    }

    public function sheets(): array
    {
        return [
            Str::plural($this->type) => new Base($this->ids, $this->type),
            $this->type . '_items' => new DocumentItems($this->ids, $this->type),
            $this->type . '_item_taxes' => new DocumentItemTaxes($this->ids, $this->type),
            $this->type . '_histories' => new DocumentHistories($this->ids, $this->type),
            $this->type . '_totals' => new DocumentTotals($this->ids, $this->type),
            $this->type . '_transactions' => new DocumentTransactions($this->ids, $this->type),
        ];
    }
}
