<?php

namespace App\Exports\Sales\Sheets;

use App\Abstracts\Export;
use App\Models\Banking\Transaction as Model;
use App\Models\Document\Document;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class InvoiceTransactions extends Export implements WithColumnFormatting
{
    public function collection()
    {
        $model = Model::with('account', 'category', 'contact', 'document')->income()->isDocument()->usingSearchString(request('search'));

        if (!empty($this->ids)) {
            $model->whereIn('document_id', (array) $this->ids);
        }

        return $model->cursor();
    }

    public function map($model): array
    {
        $document = $model->document;

        if (empty($document)) {
            return [];
        }

        $model->invoice_number = $document->document_number;
        $model->account_name = $model->account->name;
        $model->category_name = $model->category->name;
        $model->contact_email = $model->contact->email;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'invoice_number',
            'paid_at',
            'amount',
            'currency_code',
            'currency_rate',
            'account_name',
            'contact_email',
            'category_name',
            'description',
            'payment_method',
            'reference',
            'reconciled',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
