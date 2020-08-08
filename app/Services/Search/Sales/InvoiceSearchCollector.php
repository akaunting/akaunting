<?php

namespace App\Services\Search\Sales;

use App\Models\Sale\Invoice;
use App\Services\Search\SearchCollectorInterface;

class InvoiceSearchCollector implements SearchCollectorInterface
{
    private const COLOR = '#6da252';

    public function collectByKeyword(string $keyword): array
    {
        $invoices = Invoice::usingSearchString($keyword)->get();

        return array_map(static function (Invoice $invoice) {
            $label = sprintf(
                '%s - %s',
                $invoice->invoice_number,
                $invoice->contact_name
            );

            return [
                'id' => $invoice->id,
                'name' => $label,
                'type' => trans_choice('general.invoices', 1),
                'color' => self::COLOR,
                'href' => route('invoices.show', $invoice->id)
            ];
        }, iterator_to_array($invoices));
    }
}
