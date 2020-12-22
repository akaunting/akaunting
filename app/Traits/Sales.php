<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Sales
{
    /**
     * Generate next invoice number
     *
     * @return string
     */
    public function getNextInvoiceNumber()
    {
        $prefix = setting('invoice.number_prefix', 'INV-');
        $next = setting('invoice.number_next', '1');
        $digit = setting('invoice.number_digit', '5');

        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);

        return $number;
    }

    /**
     * Increase the next invoice number
     */
    public function increaseNextInvoiceNumber()
    {
        $next = setting('invoice.number_next', 1) + 1;

        setting(['invoice.number_next' => $next]);
        setting()->save();
    }

    /**
     * Get a collection invoice statuses
     *
     * @return Collection
     */
    public function getInvoiceStatuses()
    {
        $list = [
            'draft',
            'sent',
            'viewed',
            'approved',
            'partial',
            'paid',
            'overdue',
            'unpaid',
            'cancelled',
        ];

        $statuses = collect($list)->each(function ($code) {
            $item = new \stdClass();
            $item->code = $code;
            $item->name = trans('invoices.statuses.' . $code);

            return $item;
        });

        return $statuses;
    }

    public function getInvoiceFileName($invoice, $separator = '-', $extension = 'pdf')
    {
        return $this->getSafeInvoiceNumber($invoice, $separator) . $separator . time() . '.' . $extension;
    }

    public function getSafeInvoiceNumber($invoice, $separator = '-')
    {
        return Str::slug($invoice->invoice_number, $separator, language()->getShortCode());
    }
}
