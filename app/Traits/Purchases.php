<?php

namespace App\Traits;

trait Purchases
{
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
