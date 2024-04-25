<?php

namespace App\Exports\Banking;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;
use App\Http\Requests\Banking\Transaction as Request;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class Transactions extends Export implements WithColumnFormatting
{
    public $request_class = Request::class;

    public function collection()
    {
        return Model::with('account', 'category', 'contact', 'document')->collectForExport($this->ids, ['paid_at' => 'desc']);
    }

    public function map($model): array
    {
        $model->account_name = $model->account->name;
        $model->contact_email = $model->contact->email;
        $model->category_name = $model->category->name;
        $model->invoice_bill_number = $model->document->document_number ?? 0;
        $model->parent_number = Model::isRecurring()->find($model->parent_id)?->number;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'type',
            'number',
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'account_name',
            'invoice_bill_number',
            'contact_email',
            'category_name',
            'description',
            'payment_method',
            'reference',
            'reconciled',
            'parent_number',
        ];
    }

    public function columnValidations(): array
    {
        return [
            'type' => [
                'options' => array_keys(config('type.transaction'))
            ],
            // 'paid_at' => [
            //     'type' => DataValidation::TYPE_NONE,
            //     'prompt_title' => trans('general.validation_warning'),
            //     'prompt' => trans('validation.date_format', ['attribute' => 'paid_at', 'format' => 'yyyy-mm-dd']),
            //     'hide_error' => true,
            // ],
            // 'contact_email' => [
            //     'options' => $this->getDropdownOptions(Contact::class, 'email'),
            // ]
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
