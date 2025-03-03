<?php

namespace App\Traits;

use App\Interfaces\Utility\DocumentNumber;
use App\Models\Document\Document;
use App\Abstracts\View\Components\Documents\Document as DocumentComponent;
use App\Utilities\Date;
use App\Traits\Transactions;
use App\Events\Document\DocumentTemplates;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait Documents
{
    use Transactions;

    public function isRecurringDocument(): bool
    {
        $type = $this->type ?? $this->document->type ?? $this->model->type ?? 'invoice';

        return Str::endsWith($type, '-recurring');
    }

    public function isNotRecurringDocument(): bool
    {
        return ! $this->isRecurring();
    }

    public function getRecurringDocumentTypes() : array
    {
        $types = array_keys(config('type.document'));

        $recurring_types = [];

        foreach ($types as $type) {
            if (Str::endsWith($type, '-recurring')) {
                $recurring_types[] = $type;
            }
        }

        return $recurring_types;
    }

    /**
     * Deprecated. Use the DocumentNumber::getNextNumber() method instead.
     *
     * @deprecated This method is deprecated and will be removed in future versions.
     */
    public function getNextDocumentNumber(string $type): string
    {
        return app(DocumentNumber::class)->getNextNumber($type, null);
    }

    /**
     * Deprecated. Use the DocumentNumber::increaseNextNumber() method instead.
     *
     * @deprecated This method is deprecated and will be removed in future versions.
     */
    public function increaseNextDocumentNumber(string $type): void
    {
        app(DocumentNumber::class)->increaseNextNumber($type, null);
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

    public function getDocumentStatusesForFuture()
    {
        return [
            'draft',
            'sent',
            'received',
            'viewed',
            'partial',
        ];
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
        $default_key = config('type.document.' . $type . '.translation.prefix') . '.statuses.';

        $translation = DocumentComponent::getTextFromConfig($type, 'document_status', $default_key);

        if (!empty($translation)) {
            return $translation;
        }

        $alias = config('type.document.' . $type . '.alias');

        if (!empty($alias)) {
            $translation = $alias . '::' . config('type.document.' . $type . '.translation.prefix') . '.statuses';

            if (is_array(trans($translation))) {
                return $translation . '.';
            }
        }

        return 'documents.statuses.';
    }

    // This function will be remoed in the future
    protected function getSettingKey($type, $setting_key)
    {
        return $this->getDocumentSettingKey($type, $setting_key);
    }

    protected function getDocumentSettingKey($type, $setting_key)
    {
        $key = '';
        $alias = config('type.document.' . $type . '.alias');

        if (! empty($alias)) {
            $key .= $alias . '.';
        }

        $prefix = config('type.document.' . $type . '.setting.prefix');

        if (! empty($prefix)) {
            $key .= $prefix . '.' . $setting_key;
        } else {
            $key .= $setting_key;
        }

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

        $pdf_path = get_storage_path('app/temp/' . $file_name);

        // Save the PDF file into temp folder
        $pdf->save($pdf_path);

        return $pdf_path;
    }

    public function getTotalsForFutureDocuments($type = 'invoice', $documents = null)
    {
        $totals = [
            'overdue'   => 0,
            'open'      => 0,
            'draft'     => 0,
        ];

        $today = Date::today()->toDateString();

        $documents = $documents ?: Document::type($type)->with('transactions')->future();

        $documents->each(function ($document) use (&$totals, $today) {
            if (! in_array($document->status, $this->getDocumentStatusesForFuture())) {
                return;
            }

            $payments = 0;

            if ($document->status == 'draft') {
                $totals['draft'] += $document->getAmountConvertedToDefault();

                return;
            }

            if ($document->status == 'partial') {
                foreach ($document->transactions as $transaction) {
                    $payments += $transaction->getAmountConvertedToDefault();
                }
            }

            // Check if the document is open or overdue
            if ($document->due_at > $today) {
                $totals['open'] += $document->getAmountConvertedToDefault() - $payments;
            } else {
                $totals['overdue'] += $document->getAmountConvertedToDefault() - $payments;
            }
        });

        return $totals;
    }

    public function canNotifyTheContactOfDocument(Document $document): bool
    {
        $config = config('type.document.' . $document->type . '.notification');

        if (! $config['notify_contact']) {
            return false;
        }

        if (! $document->contact || ($document->contact->enabled == 0)) {
            return false;
        }

        if (empty($document->contact_email)) {
            return false;
        }

        // Check if ietf.org has MX records signaling a server with email capabilites
        $validator = new EmailValidator();
        $validations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation(),
        ]);
        if (! $validator->isValid($document->contact_email, $validations)) {
            return false;
        }

        return true;
    }

    public function getRealTypeOfDocument(string $type): string
    {
        $type = $this->getRealTypeOfRecurringDocument($type);

        return $type;
    }

    public function getRealTypeOfRecurringDocument(string $recurring_type): string
    {
        return Str::replace('-recurring', '', $recurring_type);
    }

    public function getDocumentTemplates($type)
    {
        $templates = new \stdClass();
        $templates->templates = collect();
        $config_templates = config('type.document.' . $type . '.templates', [
            [
                'id' => 'default',
                'name' => trans('settings.invoice.default'),
                'image' => asset('public/img/invoice_templates/default.png'),
                'template' => 'default',
            ],
            [
                'id' => 'classic',
                'name' => trans('settings.invoice.classic'),
                'image' => asset('public/img/invoice_templates/classic.png'),
                'template' => 'classic',
            ],
            [
                'id' => 'modern',
                'name' => trans('settings.invoice.modern'),
                'image' => asset('public/img/invoice_templates/modern.png'),
                'template' => 'modern',
            ],
        ]);

        foreach ($config_templates as $config_template) {
            $templates->templates->push($config_template);
        }

        event(new DocumentTemplates($type, $templates));

        return $templates->templates;
    }
}
