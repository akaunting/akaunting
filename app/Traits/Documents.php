<?php

namespace App\Traits;

use App\Models\Document\Document;
use App\Abstracts\View\Components\Document as DocumentComponent;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait Documents
{
    public function getNextDocumentNumber(string $type): string
    {
        if ($alias = config('type.' . $type . '.alias')) {
            $type = $alias . '.' . str_replace('-', '_', $type);
        }

        $prefix = setting("$type.number_prefix");
        $next = setting("$type.number_next");
        $digit = setting("$type.number_digit");

        return $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
    }

    public function increaseNextDocumentNumber(string $type): void
    {
        if ($alias = config('type.' . $type . '.alias')) {
            $type = $alias . '.' . str_replace('-', '_', $type);
        }

        $next = setting("$type.number_next", 1) + 1;

        setting(["$type.number_next" => $next]);
        setting()->save();
    }

    public function getDocumentStatuses(string $type): Collection
    {
        $list = [
            'invoice' => [
                'draft',
                'sent',
                'viewed',
                'approved',
                'partial',
                'paid',
                'overdue',
                'unpaid',
                'cancelled',
            ],
            'bill'    => [
                'draft',
                'received',
                'partial',
                'paid',
                'overdue',
                'unpaid',
                'cancelled',
            ],
        ];

        // @todo get dynamic path
        //$trans_key = $this->getTextDocumentStatuses($type);
        $trans_key = 'documents.statuses.';

        $statuses = collect($list[$type])->each(function ($code) use ($type, $trans_key) {
            $item = new \stdClass();
            $item->code = $code;
            $item->name = trans($trans_key . $code);

            return $item;
        });

        return $statuses;
    }

    public function getDocumentFileName(Document $document, string $separator = '-', string $extension = 'pdf'): string
    {
        return $this->getSafeDocumentNumber($document, $separator) . $separator . time() . '.' . $extension;
    }

    public function getSafeDocumentNumber(Document $document, string $separator = '-'): string
    {
        return Str::slug($document->document_number, $separator, language()->getShortCode());
    }

    protected function getTextDocumentStatuses($type)
    {
        $default_key = config('type.' . $type . '.translation.prefix') . '.statuses.';

        $translation = DocumentComponent::getTextFromConfig($type, 'document_status', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        $alias = config('type.' . $type . '.alias');

        if (!empty($alias)) {
            $translation = $alias . '::' . config('type.' . $type . '.translation.prefix') . '.statuses';

            if (is_array(trans($translation))) {
                return $translation . '.';
            }
        }

        return 'documents.statuses.';
    }

    protected function getSettingKey($type, $setting_key)
    {
        $key = '';
        $alias = config('type.' . $type . '.alias');

        if (!empty($alias)) {
            $key .= $alias . '.';
        }

        $prefix = config('type.' . $type . '.setting.prefix');


        $key .= $prefix . '.' . $setting_key;

        return $key;
    }

    public function storeDocumentPdfAndGetPath($document)
    {
        event(new \App\Events\Document\DocumentPrinting($document));

        $view = view($document->template_path, ['invoice' => $document, 'document' => $document])->render();
        $html = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        $file_name = $this->getDocumentFileName($document);

        $pdf_path = storage_path('app/temp/' . $file_name);

        // Save the PDF file into temp folder
        $pdf->save($pdf_path);

        return $pdf_path;
    }
}
