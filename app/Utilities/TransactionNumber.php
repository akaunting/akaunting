<?php

namespace App\Utilities;

use App\Interfaces\Utility\TransactionNumber as TransactionNumberInterface;
use App\Models\Banking\Transaction;
use App\Models\Common\Contact;
use Illuminate\Support\Str;

class TransactionNumber implements TransactionNumberInterface
{
    public function getNextNumber($type, $suffix = '', ?Contact $contact): string
    {
        $prefix = setting('transaction' . $suffix . '.number_prefix');
        $next   = (string) setting('transaction' . $suffix . '.number_next');
        $digit  = (int) setting('transaction' . $suffix . '.number_digit');

        $get_number = fn($prefix, $next, $digit) => $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);
        $number_exists = fn($type, $number) => $this->numberExists($type, $number);

        $transaction_number = $get_number($prefix, $next, $digit);

        if ($number_exists($type, $transaction_number)) {
            do {
                $next++;

                $transaction_number = $get_number($prefix, $next, $digit);
            } while ($number_exists($type, $transaction_number));

            setting(['transaction' . $suffix . '.number_next' => $next]);
            setting()->save();
        }

        return $transaction_number;

    }

    public function numberExists($type, $number)
    {
        $number_exists = new Transaction;

        if (Str::endsWith($type, '-recurring')) {
            $number_exists = $number_exists->isRecurring();
        }

        if (Str::endsWith($type, '-split')) {
            $number_exists = $number_exists->isSplit();
        }

        $number_exists = $number_exists->where('number', $number);

        return $number_exists->exists();
    }

    public function increaseNextNumber($type, $suffix = '', ?Contact $contact): void
    {
        $next = setting('transaction' . $suffix . '.number_next', 1) + 1;

        setting(['transaction' . $suffix . '.number_next' => $next]);
        setting()->save();
    }
}
