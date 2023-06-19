<?php

namespace App\Abstracts;

use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

abstract class ImportMultipleSheets implements ShouldQueue, WithChunkReading, WithMultipleSheets
{
    use Importable;

    public $user;

    public function __construct()
    {
        $this->user = user();
    }

    public function chunkSize(): int
    {
        return config('excel.imports.chunk_size');
    }
}
