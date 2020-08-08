<?php

namespace App\Services\Search\Purchases;

use App\Models\Purchase\Bill;
use App\Services\Search\SearchCollectorInterface;

class BillCollector implements SearchCollectorInterface
{
    private const COLOR = '#ef3232';

    public function collectByKeyword(string $keyword): array
    {
        $bills = Bill::usingSearchString($keyword)->get();

        return array_map(static function (Bill $bill) {
            return [
                'id'    => $bill->id,
                'name'  => $bill->bill_number . ' - ' . $bill->contact_name,
                'type'  => trans_choice('general.bills', 1),
                'color' => self::COLOR,
                'href'  => route('bills.show', $bill->id),
            ];
        }, iterator_to_array($bills));
    }
}
