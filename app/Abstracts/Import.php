<?php

namespace App\Abstracts;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

abstract class Import implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithMapping, WithValidation
{
    public function map($row): array
    {
        $row['company_id'] = session('company_id');

        // Make enabled field integer
        if (isset($row['enabled'])) {
            $row['enabled'] = (int) $row['enabled'];
        }

        // Make reconciled field integer
        if (isset($row['reconciled'])) {
            $row['reconciled'] = (int) $row['reconciled'];
        }

        return $row;
    }

    public function rules(): array
    {
        return [];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function onFailure(Failure ...$failures)
    {
        $sheet = Str::snake((new \ReflectionClass($this))->getShortName());

        foreach ($failures as $failure) {
            $message = trans('messages.error.import_column', [
                'message' => $failure->errors()->first(),
                'sheet' => $sheet,
                'line' => $failure->attribute(),
            ]);

            flash($message)->error()->important();
       }
    }
}
