<?php

namespace App\Imports\Common;

use App\Imports\Common\Sheets\Items as Base;
use App\Imports\Common\Sheets\ItemTaxes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class Items implements ShouldQueue, WithChunkReading, WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            'items' => new Base(),
            'item_taxes' => new ItemTaxes(),
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
