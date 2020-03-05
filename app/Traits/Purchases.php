<?php

namespace App\Traits;

trait Purchases
{
    /**
     * Generate next bill number
     *
     * @return string
     */
    public function getNextBillNumber()
    {
        $prefix = setting('bill.number_prefix', 'BIL-');
        $next = setting('bill.number_next', '1');
        $digit = setting('bill.number_digit', '5');

        $number = $prefix . str_pad($next, $digit, '0', STR_PAD_LEFT);

        return $number;
    }

    /**
     * Increase the next bill number
     */
    public function increaseNextBillNumber()
    {
        $next = setting('bill.number_next', 1) + 1;

        setting(['bill.number_next' => $next]);
        setting()->save();
    }

    /**
     * Get a collection of bill statuses
     *
     * @return Collection
     */
    public function getBillStatuses()
    {
        $list = [
            'draft',
            'received',
            'partial',
            'paid',
            'overdue',
            'unpaid',
        ];

        $statuses = collect($list)->each(function ($code) {
            $item = new \stdClass();
            $item->code = $code;
            $item->name = trans('bills.statuses.' . $code);

            return $item;
        });

        return $statuses;
    }
}
