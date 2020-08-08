<?php

namespace App\Services\Search\Purchases;

use App\Models\Banking\Transaction;
use App\Services\Search\SearchCollectorInterface;

class PaymentCollector implements SearchCollectorInterface
{
    private const COLOR = '#ef3232';

    public function collectByKeyword(string $keyword): array
    {
        $payments = Transaction::expense()
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
                'type' => trans_choice('general.payments', 1),
                'color' => self::COLOR,
                'href' => route('payments.edit', $transaction->id)
            ];
        }, iterator_to_array($payments));
    }
}
