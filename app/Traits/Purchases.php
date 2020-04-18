<?php

namespace App\Traits;

use Illuminate\Support\Str;

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
            'cancelled',
        ];

        $statuses = collect($list)->each(function ($code) {
            $item = new \stdClass();
            $item->code = $code;
            $item->name = trans('bills.statuses.' . $code);

            return $item;
        });

        return $statuses;
    }

    public function getBillFileName($bill, $separator = '-', $extension = 'pdf')
    {
        return $this->getSafeBillNumber($bill, $separator) . $separator . time() . '.' . $extension;
    }

    public function getSafeBillNumber($bill, $separator = '-')
    {
        return Str::slug($bill->bill_number, $separator, language()->getShortCode());
    }
}
