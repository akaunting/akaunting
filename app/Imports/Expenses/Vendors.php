<?php

namespace App\Imports\Incomes;

use App\Models\Common\Contact as Model;
use App\Http\Requests\Common\Contact as Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class Vendors implements ToModel, WithHeadingRow, WithMapping, WithValidation
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row['company_id'] = session('company_id');
        $row['type'] = 'vendor';

        // Make enabled field integer
        if (isset($row['enabled'])) {
            $row['enabled'] = (int) $row['enabled'];
        }

        return $row;
    }

    public function rules(): array
    {
        return (new Request())->rules();
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $message = trans('messages.error.import_column', [
                'message' => $failure->errors()->first(),
                'sheet' => 'vendors',
                'line' => $failure->attribute(),
            ]);
    
            flash($message)->error()->important();
       }
    }
}