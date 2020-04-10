<?php

namespace App\Utilities;

use App\Models\Purchase\Bill;
use App\Models\Sale\Invoice;
use Date;

class Recurring
{
    public static function reflect(&$items, $issued_date_field)
    {
        foreach ($items as $key => $item) {
            // @todo cache recurrings
            if (!$item->recurring || !empty($item->parent_id)) {
                continue;
            }

            foreach ($item->recurring->schedule() as $recurr) {
                $issued = Date::parse($item->$issued_date_field);
                $start = $recurr->getStart();

                if ($issued->format('Y') != $start->format('Y')) {
                    continue;
                }

                if (($issued->format('Y-m') == $start->format('Y-m')) && ($issued->format('d') >= $start->format('d'))) {
                    continue;
                }

                $clone = clone $item;

                $start_date = Date::parse($start->format('Y-m-d'));

                if (($clone instanceof Invoice) || ($clone instanceof Bill)) {
                    // Days between invoiced/billed and due date
                    $diff_days = Date::parse($clone->due_at)->diffInDays(Date::parse($clone->$issued_date_field));

                    $clone->due_at = $start_date->addDays($diff_days)->format('Y-m-d');
                }

                $clone->parent_id = $item->id;
                $clone->created_at = $start_date->format('Y-m-d');
                $clone->$issued_date_field = $start_date->format('Y-m-d');

                $items->push($clone);
            }
        }
    }
}
