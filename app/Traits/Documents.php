<?php

namespace App\Traits;

use App\Models\Document\Document;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait Documents
{
    public function getNextDocumentNumber(string $type): string
    {
        $prefix = setting("$type.number_prefix");
        $next = setting("$type.number_next");
        $digit = setting("$type.number_digit");

        return $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
    }

    public function increaseNextDocumentNumber(string $type): void
    {
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

        $statuses = collect($list[$type])->each(function ($code) use ($type) {
            $item = new \stdClass();
            $item->code = $code;
            $item->name = trans(Str::plural($type) . '.statuses.' . $code);

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
}
