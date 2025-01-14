<?php

namespace App\Abstracts;

use App\Abstracts\Http\FormRequest;
use App\Events\Export\HeadingsPreparing;
use App\Events\Export\RowsPreparing;
use App\Notifications\Common\ExportFailed;
use App\Utilities\Date;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

abstract class Export implements FromCollection, HasLocalePreference, ShouldAutoSize, ShouldQueue, WithHeadings, WithMapping, WithTitle, WithStrictNullComparison, WithEvents
{
    use Exportable;

    public $ids;

    public $fields;

    public $user;

    public $request_class = null;

    public $column_count; //number of columns to be auto sized

    public $column_validations; //selects should have column_name and options

    public $row_count; //number of rows that will have the dropdown

    public function __construct($ids = null)
    {
        $this->ids = $ids;
        $this->fields = $this->fields();
        $this->column_validations = $this->columnValidations();
        $this->column_count = config('excel.exports.column_count');
        $this->row_count = config('excel.exports.row_count');
        $this->user = user();
    }

    public function title(): string
    {
        return Str::snake((new \ReflectionClass($this))->getShortName());
    }

    public function fields(): array
    {
        return [];
    }

    public function map($model): array
    {
        $map = [];

        $date_fields = ['paid_at', 'invoiced_at', 'billed_at', 'due_at', 'issued_at', 'transferred_at'];

        $evil_chars = ['=', '+', '-', '@'];

        foreach ($this->fields as $field) {
            $value = $model->$field;

            // created_by is equal to the owner id. Therefore, the value in export is owner email.
            if ($field == 'created_by') {
                $value = $model->owner->email ?? null;
            }

            if (in_array($field, $date_fields)) {
                $value = ExcelDate::PHPToExcel(Date::parse($value)->format('Y-m-d'));
            }

            // Prevent CSV injection https://security.stackexchange.com/a/190848
            if (Str::startsWith($value, $evil_chars)) {
                $value = "'" . $value;
            }

            $map[] = $value;
        }

        return $map;
    }

    public function headings(): array
    {
        event(new HeadingsPreparing($this));

        return $this->fields;
    }

    public function prepareRows($rows)
    {
        event(new RowsPreparing($this, $rows));

        return $rows;
    }

    public function preferredLocale()
    {
        if (! $this->user) {
            return setting('default.locale');
        }

        return $this->user->locale;
    }

    public function failed(\Throwable $exception): void
    {
        if (! $this->user) {
            return;
        }

        $this->user->notify(new ExportFailed($exception->getMessage()));
    }

    public function columnValidations(): array
    {
        return [];
    }

    public function afterSheet($event)
    {
        $condition = class_exists($this->request_class)
            ? ! ($request = new $this->request_class) instanceof FormRequest
            : true;


        if (empty($this->column_validations) && $condition) {
            return [];
        }

        $alphas = range('A', 'Z');

        foreach ($this->fields as $key => $value) {
            $drop_column = $alphas[$key];

            if ($this->setColumnValidations($drop_column, $event, $value)) {
                continue;
            };

            $this->validationWarning($drop_column, $event, $value, $request);
        }
    }

    public function setColumnValidations($drop_column, $event, $value)
    {
        if (! isset($this->column_validations[$value])) {
            return false;
        }

        $column_validation = $this->column_validations[$value];

        // set dropdown list for first data row
        $validation = $event->sheet->getCell("{$drop_column}2")->getDataValidation();

        $validation->setType($column_validation['type'] ?? DataValidation::TYPE_LIST);

        if (empty($column_validation['hide_prompt'])) {
            $validation->setAllowBlank($column_validation['allow_blank'] ?? false);
            $validation->setShowInputMessage($column_validation['show_input_message'] ?? true);
            $validation->setPromptTitle($column_validation['prompt_title'] ?? null);
            $validation->setPrompt($column_validation['prompt'] ?? null);
        }

        if (empty($column_validation['hide_error'])) {
            $validation->setErrorStyle($column_validation['error_style'] ?? DataValidation::STYLE_INFORMATION);
            $validation->setShowErrorMessage($column_validation['show_error_message'] ?? true);
            $validation->setErrorTitle($column_validation['error_title'] ?? null);
            $validation->setError($column_validation['error'] ?? null);
        }

        if (! empty($column_validation['options'])) {
            $validation->setFormula1(sprintf('"%s"', implode(',', $column_validation['options'])));
            $validation->setShowDropDown($column_validation['show_dropdown'] ?? true);
        }

        // clone validation to remaining rows
        for ($i = 3; $i <= $this->row_count; $i++) {
            $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
        }

        // set columns to autosize
        for ($i = 1; $i <=  $this->column_count; $i++) {
            $column = Coordinate::stringFromColumnIndex($i);
            $event->sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return true;
    }

    public function validationWarning($drop_column, $event, $value, $request)
    {
        $rules = $this->prepareRules($request->rules());

        if (! isset($rules[$value])) {
            return false;
        }

        $rule = explode('|', $rules[$value]);

        $prompt = '';

        foreach ($rule as $r) {
            if (strpos($r, 'unique') !== false) {
                $r = 'unique';
            }

            if (strpos($r, 'amount') !== false) {
                $r = 'double';
            }

            if (strpos($r, 'date_format') !== false) {
                $prompt = $prompt . trans('validation.date_format', [
                    'attribute' => $value,
                    'format' => str_replace('date_format:', '', $r)
                ]) . ' ';
            }

            if (strpos($r, 'required_without') !== false) {
                $prompt = $prompt . trans('validation.required_without', [
                    'attribute' => $value,
                    'values' => str_replace('required_without:', '', $r)
                ]) . ' ';
            }

            if (in_array($r, ['required', 'email', 'integer', 'unique', 'date_format', 'double'])) {
                $prompt = $prompt . trans('validation.' . $r, ['attribute' => $value]) . ' ';
            }
        }

        $validation = $event->sheet->getCell("{$drop_column}2")->getDataValidation();

        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setPromptTitle(trans('general.validation_warning'));
        $validation->setPrompt($prompt ?? null);

        for ($i = 3; $i <= $this->row_count; $i++) {
            $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
        }

        for ($i = 1; $i <=  $this->column_count; $i++) {
            $column = Coordinate::stringFromColumnIndex($i);
            $event->sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    public function getDropdownOptions($model, $select): array
    {
        $limit = 253;
        $selects = [];
        $totalLength = 0;

        $model::select($select)->each(function ($row) use (&$selects, &$totalLength, $limit, $select) {
            $nameLength = mb_strlen($row->$select);

            if ($totalLength + $nameLength <= $limit && $nameLength !== 0) {
                $selects[] = $row->$select;
                $totalLength += $nameLength;
            }
        });

        return $selects;
    }

    /**
     * You can override this method to add custom rules for each row.
     */
    public function prepareRules(array $rules): array
    {
        return $rules;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $this->afterSheet($event);
            },
        ];
    }
}
