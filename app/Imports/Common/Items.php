<?php

namespace App\Imports\Common;

use App\Models\Common\Item as Model;
use App\Http\Requests\Common\Item as Request;
use App\Jobs\Common\CreateItem;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class Items implements ToModel, WithHeadingRow, WithMapping, WithValidation
{
    public function model(array $row)
    {
        return new Model($row);
        //$request = (new Request())->merge($row);

        //return dispatch_now(new CreateItem($request));
    }

    public function map($row): array
    {
        $row['company_id'] = session('company_id');

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
                'sheet' => 'items',
                'line' => $failure->attribute(),
            ]);

            flash($message)->error()->important();
       }
    }
}
