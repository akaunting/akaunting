<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'Søndag',
    'Mandag',
    'Tirsdag',
    'Onsdag',
    'Torsdag',
    'Fredag',
    'Lørdag',
);
$months = array(
    'Januar',
    'Februar',
    'Mars',
    'April',
    'Mai',
    'Juni',
    'Juli',
    'August',
    'September',
    'Oktober',
    'November',
    'Desember',
);

return array(
    'Unable to fully convert this rrule to text.' => 'Kunne ikke konvertere rrule til tekst.',
    'for %count% times' => '%count% ganger',
    'for one time' => 'en gang',
    '(~ approximate)' => '(~ omtrent)',
    'until %date%' => 'frem til %date%', // e.g. every year until July 4, 2014
    'day_date' => function ($str, $params) use ($days, $months) { // outputs a day date, e.g. July 4, 2014
        return $months[date('n', $params['date']) - 1] . ' '. date('j, Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months) { // outputs a day month, e.g. July 4
        return $months[$params['month'] - 1] . ' '. $params['day'];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 'og',
    'or' => 'eller',
    'in_month' => 'i', // e.g. weekly in January, May and August
    'in_week' => 'i', // e.g. yearly in week 3
    'on' => 'på', // e.g. every day on Tuesday, Wednesday and Friday
    'the_for_monthday' => 'den', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => 'den', // e.g. monthly on the 4th Monday
    'on the' => 'på den', // e.g. every year on the 1st and 200th day
    'of_the_month' => 'i måneden', // e.g. every year on the 2nd or 3rd of the month
    'every %count% years' => 'hvert %count% år',
    'every year' => 'årlig',
    'every_month_list' => 'hver', // e.g. every January, May and August
    'every %count% months' => 'hver %count% måned',
    'every month' => 'månedlig',
    'every %count% weeks' => 'hver %count% uke',
    'every week' => 'ukentlig',
    'every %count% days' => 'hver %count% dag',
    'every day' => 'daglig',
    'last' => 'siste', // e.g. 2nd last Friday
    'days' => 'dager',
    'day' => 'dag',
    'weeks' => 'uker',
    'week' => 'uke',
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
            $abbreviation = 'sist';
        } else {
            if ($isNegative) {
                $number = abs($number);
                $suffix = ' til den siste';
            }

            if (($number % 100) >= 11 && ($number % 100) <= 13) {
                $abbreviation = $number.'th';
            } else {
                $abbreviation = $number.$ends[$number % 10];
            }
        }

        if (!empty($params['has_negatives'])) {
            $suffix .= ' dag';
        }

        return $abbreviation . $suffix;
    },
);
