<?php

namespace App\Imports\Incomes\Sheets;

use App\Models\Income\InvoiceItemTax as Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Validators\Failure;

class InvoiceItemTaxes implements ToModel, WithHeadingRow, WithMapping
{
    public function model(array $row)
    {
        return new Model($row);
    }

    public function map($row): array
    {
        $row['company_id'] = session('company_id');

        return $row;
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $message = trans('messages.error.import_column', [
                'message' => $failure->errors()->first(),
                'sheet' => 'invoice_item_taxes',
                'line' => $failure->attribute(),
            ]);
    
            flash($message)->error()->important();
       }
    }
}