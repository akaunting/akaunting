<?php

namespace App\Exports\Incomes;

use App\Models\Common\Contact as Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class Customers implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping, WithTitle
{
    public $ids;

    public function __construct($ids = null)
    {
        $this->ids = $ids;
    }

    public function collection()
    {
        $model = Model::type('customer')->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('id', (array) $this->ids);
        }

        return $model->get();
    }

    public function map($model): array
    {
        return [
            $model->name,
            $model->email,
            $model->user_id,
            $model->tax_number,
            $model->phone,
            $model->address,
            $model->website,
            $model->currency_code,
            $model->reference,
            $model->enabled,
        ];
    }

    public function headings(): array
    {
        return [
            'name',
            'email',
            'user_id',
            'tax_number',
            'phone',
            'address',
            'website',
            'currency_code',
            'reference',
            'enabled',
        ];
    }

    public function title(): string
    {
        return 'customers';
    }
}