<?php

namespace App\Services\Search\Sales;

use App\Models\Banking\Transaction;
use App\Services\Search\SearchCollectorInterface;

class RevenueSearchCollector implements SearchCollectorInterface
{
    private const COLOR = '#00c0ef';

    public function collectByKeyword(string $keyword): array
    {
        $transactions = Transaction::income()
            ->usingSearchString($keyword)
            ->get();

        return array_map(static function (Transaction $transaction) {
            $contact = $transaction->contact()->get()->first()->name;

            $label = sprintf(
                '%s - %s - %s',
                $transaction->paid_at,
                $contact,
                money($transaction->getAmount(), $transaction->currency_code, true)
            );

            return [
                'id' => $transaction->id,
                'name' => $label,
                'type' => trans_choice('general.revenues', 1),
                'color' => self::COLOR,
                'href' => route('revenues.edit', $transaction->id)
             ];
        }, iterator_to_array($transactions));
    }
}
