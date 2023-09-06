<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'Sonntag',
    'Montag',
    'Dienstag',
    'Mittwoch',
    'Donnerstag',
    'Freitag',
    'Samstag',
);
$months = array(
    'Januar',
    'Februar',
    'März',
    'April',
    'Mai',
    'Juni',
    'Juli',
    'August',
    'September',
    'Oktober',
    'November',
    'Dezember',
);

return array(
    'Unable to fully convert this rrule to text.' => 'RRule kann nicht vollständig zu Text konvertiert werden.',
    'for %count% times' => '%count% Mal',
    'for one time' => 'einmal',
    '(~ approximate)' => '(~ ungefähr)',
    'until %date%' => 'bis %date%', // e.g. every year until July 4, 2014
    'day_date' => function ($str, $params) use ($days, $months) { // outputs a day date, e.g. 4. Juli, 2014
        return date('j. ', $params['date']) . $months[date('n', $params['date']) - 1] . date(', Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months) { // outputs a day month, e.g. July 4
        return $params['day'].'. '.$months[$params['month'] - 1];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 'und',
    'or' => 'oder',
    'in_month' => 'im', // e.g. weekly in January, May and August
    'in_week' => 'in', // e.g. yearly in week 3
    'on' => 'am', // e.g. every day on Tuesday, Wednesday and Friday
    'the_for_monthday' => 'dem', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => '', // e.g. monthly on the 4th Monday
    'on the' => 'am', // e.g. every year on the 1st and 200th day
    'of_the_month' => 'des Monats', // e.g. every year on the 2nd or 3rd of the month
    'every %count% years' => 'alle %count% Jahre',
    'every year' => 'jährlich',
    'every_month_list' => 'jeden', // e.g. every January, May and August
    'every %count% months' => 'alle %count% Monate',
    'every month' => 'monatlich',
    'every %count% weeks' => 'alle %count% Wochen',
    'every week' => 'wöchentlich',
    'every %count% days' => 'alle %count% Tage',
    'every day' => 'täglich',
    'every %count% hours' => 'alle %count% Stunden',
    'every hour' => 'stündlich',
    'last' => 'letzte', // e.g. 2nd last Friday
    'days' => 'Tage',
    'day' => 'Tag',
    'weeks' => 'Wochen',
    'week' => 'Woche',
    'hours' => 'Stunden',
    'hour' => 'stündlich',
    // formats a number with a prefix e.g. every year on the 1st and 200th day
    // negative numbers should be handled as in '5th to the last' or 'last'
    //
    // if has_negatives is true in the params, it is good form to add 'day' after
    // each number, as in: 'every month on the 5th day or 2nd to the last day' or
    // it may be confusing like 'every month on the 5th or 2nd to the last day'
    'ordinal_number' => function ($str, $params) {
        $number = $params['number'];

        $suffix = '';
        $isNegative = $number < 0;

        if ($number == -1) {
            $abbreviation = 'letzten';
        } elseif ($number == -2) {
            $abbreviation = 'vorletzten';
        } elseif ($number == -3) {
            $abbreviation = 'drittletzten';
        } elseif ($number == -4) {
            $abbreviation = 'viertletzten';
        } elseif ($number == -5) {
            $abbreviation = 'fünftletzten';
        } elseif ($number == -6) {
            $abbreviation = 'sechstletzten';
        } elseif ($number == -7) {
            $abbreviation = 'siebtletzten';
        } elseif ($number == -8) {
            $abbreviation = 'achtletzten';
        } elseif ($number == -9) {
            $abbreviation = 'neuntletzten';
        } elseif ($number == -10) {
            $abbreviation = 'zehntletzten';
        } elseif ($number == -11) {
            $abbreviation = 'elftletzten';
        } elseif ($isNegative) {
            $number = abs($number);
            $abbreviation = $number . 't letzten';
        } else {
            $abbreviation = $number . '.';
        }

        if (!empty($params['has_negatives']) && $isNegative) {
            $suffix .= ' Tag';
        }

        return $abbreviation . $suffix;
    },
);
