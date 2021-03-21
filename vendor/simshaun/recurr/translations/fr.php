<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'dimanche',
    'lundi',
    'mardi',
    'mercredi',
    'jeudi',
    'vendredi',
    'samedi',
);
$months = array(
    'janvier',
    'février',
    'mars',
    'avril',
    'mai',
    'juin',
    'juillet',
    'août',
    'septembre',
    'octobre',
    'novembre',
    'décembre',
);

return array(
    'Unable to fully convert this rrule to text.' => 'Cette règle de récurrence n\'a pas pu être convertie en texte.',
    'for %count% times' => '%count% fois',
    'for one time' => 'une fois',
    '(~ approximate)' => '(~ approximation)',
    'until %date%' => 'jusqu\'au %date%', // e.g. every year until July 4, 2014
    'day_date' => function ($str, $params) use ($days, $months) { // outputs a day date, e.g. 4 juillet, 2014
        return date('j ', $params['date']) . $months[date('n', $params['date']) - 1] . date(', Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months) { // outputs a day month, e.g. July 4
        return $params['day'].' '.$months[$params['month'] - 1];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 'et',
    'or' => 'ou',
    'in_month' => 'en', // e.g. weekly in January, May and August
    'in_week' => 'en', // e.g. yearly in week 3
    'on' => 'le', // e.g. every day on Tuesday, Wednesday and Friday
    'the_for_monthday' => 'le', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => '', // e.g. monthly on the 4th Monday
    'on the' => 'le', // e.g. every year on the 1st and 200th day
    'of_the_month' => 'du mois', // e.g. every year on the 2nd or 3rd of the month
    'every %count% years' => 'tous les %count% ans',
    'every year' => 'chaque année',
    'every_month_list' => 'chaque', // e.g. every January, May and August
    'every %count% months' => 'tous les %count% mois',
    'every month' => 'chaque mois',
    'every %count% weeks' => 'toutes les %count% semaines',
    'every week' => 'chaque semaine',
    'every %count% days' => 'tous les %count% jours',
    'every day' => 'chaque jour',
    'every %count% hours' => 'toutes les %count% heures',
    'every hour' => 'chaque heure',
    'last' => 'dernier', // e.g. 2nd last Friday
    'days' => 'jours',
    'day' => 'jour',
    'weeks' => 'semaines',
    'week' => 'semaine',
    'hours' => 'heures',
    'hour' => 'heure',
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
            $abbreviation = 'dernier';
        } elseif ($number == -2) {
            $abbreviation = 'avant dernier';
        } elseif ($isNegative) {
            $number = abs($number);
            $abbreviation = $number . 'ème au dernier';
        } elseif ($number == 1 && (!$params['day_in_month'])) {
            $abbreviation = $number . 'er';
        } else if (!$params['day_in_month']) {
            $abbreviation = $number . 'ème';
        }
        else {
            $abbreviation = $number;
        }

        if (!empty($params['has_negatives'])) {
            $suffix .= ' jour';
        }

        return $abbreviation . $suffix;
    },
);
