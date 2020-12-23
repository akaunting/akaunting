<?php

namespace App\Imports\Document;

use App\Imports\Document\Sheets\Documents as Base;
use App\Imports\Document\Sheets\DocumentItems;
use App\Imports\Document\Sheets\DocumentItemTaxes;
use App\Imports\Document\Sheets\DocumentHistories;
use App\Imports\Document\Sheets\DocumentTotals;
use App\Imports\Document\Sheets\DocumentTransactions;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Documents implements WithMultipleSheets
{
    /**
     * @var string
     */
    protected $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function sheets(): array
    {
        return [
            Str::plural($this->type) => new Base($this->type),
            $this->type . '_items' => new DocumentItems($this->type),
            $this->type . '_item_taxes' => new DocumentItemTaxes($this->type),
            $this->type . '_histories' => new DocumentHistories($this->type),
            $this->type . '_totals' => new DocumentTotals($this->type),
            $this->type . '_transactions' => new DocumentTransactions($this->type),
        ];
    }
}
