<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'domenica',
    'lunedì',
    'martedì',
    'mercoledì',
    'giovedì',
    'venerdì',
    'sabato',
);
$months = array(
    'gennaio',
    'febbraio',
    'marzo',
    'aprile',
    'maggio',
    'giugno',
    'luglio',
    'agosto',
    'settembre',
    'ottobre',
    'novembre',
    'dicembre',
);

return array(
    'Unable to fully convert this rrule to text.' => 'Non è possibile convertire questo rrule in testo.',
    'for %count% times' => 'per %count% volte',
    'for one time' => 'per una volta',
    '(~ approximate)' => '(~ approssimato)',
    'until %date%' => 'fino al %date%', // e.g. every year until July 4, 2014
    'day_date' => function ($str, $params) use ($days, $months) { // outputs a day date, e.g. 4 luglio, 2014
        return date('j ', $params['date']) . $months[date('n', $params['date']) - 1] . date(', Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months) { // outputs a day month, e.g. July 4
        return $params['day'].' '.$months[$params['month'] - 1];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 'e',
    'or' => 'o',
    'in_month' => 'in', // e.g. weekly in January, May and August
    'in_week' => 'in', // e.g. yearly in week 3
    'on' => 'il', // e.g. every day on Tuesday, Wednesday and Friday
    'the_for_monthday' => 'il', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => 'il', // e.g. monthly on the 4th Monday
    'on the' => 'il', // e.g. every year on the 1st and 200th day
    'of_the_month' => 'del mese', // e.g. every year on the 2nd or 3rd of the month
    'every %count% years' => 'ogni %count% anni',
    'every year' => 'ogni anno',
    'every_month_list' => 'ogni', // e.g. every January, May and August
    'every %count% months' => 'ogni %count% mesi',
    'every month' => 'ogni mese',
    'every %count% weeks' => 'ogni %count% settimane',
    'every week' => 'ogni settimana',
    'every %count% days' => 'ogni %count% giorni',
    'every day' => 'ogni giorno',
    'every %count% hours' => 'ogni %count% ore',
    'every hour' => 'ogni ora',
    'last' => 'scorso', // e.g. 2nd last Friday
    'days' => 'giorni',
    'day' => 'giorno',
    'weeks' => 'settimane',
    'week' => 'settimana',
    'hours' => 'ore',
    'hour' => 'ora',
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
            $abbreviation = 'ultimo';
        } elseif ($number == -2) {
            $abbreviation = 'penultimo';
        } elseif ($number == -3) {
            $abbreviation = 'terzultimo';
        } elseif ($number == -4) {
            $abbreviation = 'quarto ultimo';
        } elseif ($number == -5) {
            $abbreviation = 'quinta ultimo';
        } elseif ($number == -6) {
            $abbreviation = 'sesto ultimo';
        } elseif ($number == -7) {
            $abbreviation = 'settimo ultimo';
        } elseif ($number == -8) {
            $abbreviation = 'otto ultimo';
        } elseif ($number == -9) {
            $abbreviation = 'nono ultimo';
        } elseif ($number == -10) {
            $abbreviation = 'decimo ultimo';
        } elseif ($number == -11) {
            $abbreviation = 'undici ultimo';
        } elseif ($isNegative) {
            $number = abs($number);
            $abbreviation = $number . ' ultimo';
        } else {
            $abbreviation = $number;
        }

        if (!empty($params['has_negatives'])) {
            $suffix .= ' giorno';
        }

        return $abbreviation . $suffix;
    },
);
