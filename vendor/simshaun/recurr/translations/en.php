<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'Sunday',
    'Monday',
    'Tuesday',
    'Wednesday',
    'Thursday',
    'Friday',
    'Saturday',
);
$months = array(
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
);

return array(
    'Unable to fully convert this rrule to text.' => 'Unable to fully convert this rrule to text.',
    'for %count% times' => 'for %count% times',
    'for one time' => 'once',
    '(~ approximate)' => '(~ approximate)',
    'until %date%' => 'until %date%', // e.g. every year until July 4, 2014
    'day_date' => function ($str, $params) use ($days, $months) { // outputs a day date, e.g. July 4, 2014
        return $months[date('n', $params['date']) - 1] . ' '. date('j, Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months) { // outputs a day month, e.g. July 4
        return $months[$params['month'] - 1] . ' '. $params['day'];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 'and',
    'or' => 'or',
    'in_month' => 'in', // e.g. weekly in January, May and August
    'in_week' => 'in', // e.g. yearly in week 3
    'on' => 'on', // e.g. every day on Tuesday, Wednesday and Friday
    'the_for_monthday' => 'the', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => 'the', // e.g. monthly on the 4th Monday
    'on the' => 'on the', // e.g. every year on the 1st and 200th day
    'of_the_month' => 'of the month', // e.g. every year on the 2nd or 3rd of the month
    'every %count% years' => 'every %count% years',
    'every year' => 'yearly',
    'every_month_list' => 'every', // e.g. every January, May and August
    'every %count% months' => 'every %count% months',
    'every month' => 'monthly',
    'every %count% weeks' => 'every %count% weeks',
    'every week' => 'weekly',
    'every %count% days' => 'every %count% days',
    'every day' => 'daily',
    'every %count% hours' => 'every %count% hours',
    'every hour' => 'hourly',
    'last' => 'last', // e.g. 2nd last Friday
    'days' => 'days',
    'day' => 'day',
    'weeks' => 'weeks',
    'week' => 'week',
    'hours' => 'hours',
    'hour' => 'hour',
    // formats a number with a prefix e.g. every year on the 1st and 200th day
    // negative numbers should be handled as in '5th to the last' or 'last'
    //
    // if has_negatives is true in the params, it is good form to add 'day' after
    // each number, as in: 'every month on the 5th day or 2nd to the last day' or
    // it may be confusing like 'every month on the 5th or 2nd to the last day'
    'ordinal_number' => function ($str, $params) {
        $number = $params['number'];

        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        $suffix = '';

        $isNegative = $number < 0;

        if ($number == -1) {
            $abbreviation = 'last';
        } else {
            if ($isNegative) {
                $number = abs($number);
                $suffix = ' to the last';
            }

            if (($number % 100) >= 11 && ($number % 100) <= 13) {
                $abbreviation = $number.'th';
            } else {
                $abbreviation = $number.$ends[$number % 10];
            }
        }

        if (!empty($params['has_negatives'])) {
            $suffix .= ' day';
        }

        return $abbreviation . $suffix;
    },
);
