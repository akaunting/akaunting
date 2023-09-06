<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'zondag',
    'maandag',
    'dinsdag',
    'woensdag',
    'donderdag',
    'vrijdag',
    'zaterdag',
);
$months = array(
    'januari',
    'februari',
    'maart',
    'april',
    'mei',
    'juni',
    'juli',
    'augustus',
    'september',
    'oktober',
    'november',
    'december',
);

return array(
    'Unable to fully convert this rrule to text.' => 'Unable to fully convert this rrule to text.',
    'for %count% times' => 'voor %count% keer',
    'for one time' => 'eenmalig',
    '(~ approximate)' => '(~ ongeveer)',
    'until %date%' => 'tot en met %date%', // e.g. every year until July 4, 2014
    'day_date' => function ($str, $params) use ($days, $months) { // outputs a day date, e.g. July 4, 2014
        return date('j', $params['date']).' '.$months[date('n', $params['date']) - 1] . ' '. date('Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months) { // outputs a day month, e.g. July 4
        return $params['day'].' '.$months[$params['month'] - 1];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 'en',
    'or' => 'of',
    'in_month' => 'op', // e.g. weekly in January, May and August
    'in_week' => 'op', // e.g. yearly in week 3
    'on' => 'op', // e.g. every day on Tuesday, Wednesday and Friday
    'the_for_monthday' => 'de', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => 'de', // e.g. monthly on the 4th Monday
    'on the' => 'op de', // e.g. every year on the 1st and 200th day
    'of_the_month' => 'van de maand', // e.g. every year on the 2nd or 3rd of the month
    'every %count% years' => 'elke %count% jaar',
    'every year' => 'jaarlijks',
    'every_month_list' => 'elke', // e.g. every January, May and August
    'every %count% months' => 'elke %count% maanden',
    'every month' => 'maandelijks',
    'every %count% weeks' => 'elke %count% weken',
    'every week' => 'wekelijks',
    'every %count% days' => 'elke %count% dagen',
    'every day' => 'dagelijks',
    'last' => 'laatste', // e.g. 2nd last Friday
    'days' => 'dagen',
    'day' => 'dag',
    'weeks' => 'weken',
    'week' => 'week',
    // formats a number with a prefix e.g. every year on the 1st and 200th day
    // negative numbers should be handled as in '5th to the last' or 'last'
    //
    // if has_negatives is true in the params, it is good form to add 'day' after
    // each number, as in: 'every month on the 5th day or 2nd to the last day' or
    // it may be confusing like 'every month on the 5th or 2nd to the last day'
    'ordinal_number' => function ($str, $params) {
        $number = $params['number'];

        $ends = array('ste', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de', 'de');
        $suffix = '';

        $isNegative = $number < 0;

        if ($number == -1) {
            $abbreviation = 'laatste';
        } else {
            if ($isNegative) {
                $number = abs($number);
                $suffix = ' na laatste';
            }

            if (($number % 100) >= 11 && ($number % 100) <= 13) {
                $abbreviation = $number.'ste';
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
