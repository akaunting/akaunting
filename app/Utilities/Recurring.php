<?php

namespace App\Utilities;

use App\Models\Document\Document;
use App\Traits\DateTime;
use Date;

class Recurring
{
    use DateTime;

    public static function reflect(&$items, $issued_date_field)
    {
        $financial_year = (new static)->getFinancialYear();

        foreach ($items as $key => $item) {
            // @todo cache recurrings
            if (!$item->recurring || !empty($item->parent_id)) {
                continue;
            }

            foreach ($item->recurring->getRecurringSchedule(false) as $schedule) {
                $issued = Date::parse($item->$issued_date_field);
                $start = $schedule->getStart();
                $start_date = Date::parse($start->format('Y-m-d'));

                if (($issued->format('Y-m') == $start->format('Y-m')) && ($issued->format('d') >= $start->format('d'))) {
                    continue;
                }

                if ($start_date->lessThan($financial_year->getStartDate()) || $start_date->greaterThan($financial_year->getEndDate())) {
                    continue;
                }

                $clone = clone $item;

                if ($clone instanceof Document) {
                    // Days between invoiced/billed and due date
                    $diff_days = Date::parse($clone->due_at)->diffInDays(Date::parse($clone->$issued_date_field));

                    $clone->due_at = $start_date->copy()->addDays($diff_days)->format('Y-m-d');
                }

                $clone->parent_id = $item->id;
                $clone->created_at = $start_date->format('Y-m-d');
                $clone->$issued_date_field = $start_date->format('Y-m-d');

                $items->push($clone);
            }
        }
    }
}
