<?php

namespace App\Abstracts;

use App\Traits\Import as ImportHelper;
use App\Utilities\Date;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

abstract class Import implements HasLocalePreference, ShouldQueue, SkipsEmptyRows, WithChunkReading, WithHeadingRow, WithLimit, WithMapping, WithValidation, ToModel
{
    use Importable, ImportHelper;

    public $user;

    public function __construct()
    {
        $this->user = user();
    }

    public function map($row): array
    {
        $row['company_id'] = company_id();
        $row['created_by'] = $this->user->id;

        // Make enabled field integer
        if (isset($row['enabled'])) {
            $row['enabled'] = (int) $row['enabled'];
        }

        // Make reconciled field integer
        if (isset($row['reconciled'])) {
            $row['reconciled'] = (int) $row['reconciled'];
        }

        $date_fields = ['paid_at', 'invoiced_at', 'billed_at', 'due_at', 'issued_at', 'created_at', 'transferred_at'];
        foreach ($date_fields as $date_field) {
            if (!isset($row[$date_field])) {
                continue;
            }

            try {
                $row[$date_field] = Date::parse(ExcelDate::excelToDateTimeObject($row[$date_field]))
                                        ->format('Y-m-d H:i:s');
            } catch (InvalidFormatException | \Exception $e) {
                Log::info($e->getMessage());
            }
        }

        return $row;
    }

    public function rules(): array
    {
        return [];
    }

    public function chunkSize(): int
    {
        return config('excel.imports.chunk_size');
    }

    public function limit(): int
    {
        return config('excel.imports.row_limit');
    }

    public function isNotValid($row)
    {
        return Validator::make($row, $this->rules())->fails();
    }

    public function isEmpty($row, $fields)
    {
        $fields = Arr::wrap($fields);

        foreach ($fields as $field) {
            if (!empty($row[$field])) {
                continue;
            }

            return true;
        }

        return false;
    }

    public function preferredLocale()
    {
        return $this->user->locale;
    }

    protected function replaceForBatchRules(array $rules): array
    {
        $dependent_rules = [
            'after:',
            'after_or_equal:',
            'before:',
            'before_or_equal:',
            'different:',
            'exclude_if:',
            'exclude_unless:',
            'gt:',
            'gte:',
            'in_array:',
            'lt:',
            'lte:',
            'prohibited_if:',
            'prohibited_unless:',
            'required_if:',
            'required_unless:',
            'required_with:',
            'required_with_all:',
            'required_without:',
            'required_without_all:',
            'same:',
        ];

        $batch_rules = [
            'after:*.',
            'after_or_equal:*.',
            'before:*.',
            'before_or_equal:*.',
            'different:*.',
            'exclude_if:*.',
            'exclude_unless:*.',
            'gt:*.',
            'gte:*.',
            'in_array:*.',
            'lt:*.',
            'lte:*.',
            'prohibited_if:*.',
            'prohibited_unless:*.',
            'required_if:*.',
            'required_unless:*.',
            'required_with:*.',
            'required_with_all:*.',
            'required_without:*.',
            'required_without_all:*.',
            'same:*.',
        ];

        return str_replace($dependent_rules, $batch_rules, $rules);
    }
}
