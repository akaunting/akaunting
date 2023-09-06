<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'domingo',
    'lunes',
    'martes',
    'miércoles',
    'jueves',
    'viernes',
    'sábado',
);
$months = array(
    'enero',
    'febrero',
    'marzo',
    'abril',
    'mayo',
    'junio',
    'julio',
    'agosto',
    'septiembre',
    'octubre',
    'noviembre',
    'diciembre',
);

return array(
    'Unable to fully convert this rrule to text.' => 'No se puede convertir completamente este RRULE al texto.',
    'for %count% times' => 'para %count% veces',
    'for one time' => 'por una vez',
    '(~ approximate)' => '(~ aproximado)',
    'until %date%' => 'hasta %date%', // e.g. every year until July 4, 2014
    'day_date' => function ($str, $params) use ($days, $months) { // outputs a day date, e.g. July 4, 2014
        return $months[date('n', $params['date']) - 1] . ' '. date('j, Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months) { // outputs a day month, e.g. July 4
        return $months[$params['month'] - 1] . ' '. $params['day'];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 'y',
    'or' => 'o',
    'in_month' => 'en', // e.g. weekly in January, May and August
    'in_week' => 'en', // e.g. yearly in week 3
    'on' => 'en', // e.g. every day on Tuesday, Wednesday and Friday
    'the_for_monthday' => 'el', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => 'en el', // e.g. monthly on the 4th Monday
    'on the' => 'en el', // e.g. every year on the 1st and 200th day
    'of_the_month' => 'del mes', // e.g. every year on the 2nd or 3rd of the month
    'every %count% years' => 'cada %count% años',
    'every year' => 'anual',
    'every_month_list' => 'cada', // e.g. every January, May and August
    'every %count% months' => 'cada %count% meses',
    'every month' => 'mensual',
    'every %count% weeks' => 'cada %count% semanas',
    'every week' => 'cada semana',
    'every %count% days' => 'cada %count% días',
    'every day' => 'diariamente',
    'last' => 'pasado', // e.g. 2nd last Friday
    'days' => 'día',
    'day' => 'el día',
    'weeks' => 'semanas',
    'week' => 'semana',
    // formats a number with a prefix e.g. every year on the 1st and 200th day
    // negative numbers should be handled as in '5th to the last' or 'last'
    //
    // if has_negatives is true in the params, it is good form to add 'day' after
    // each number, as in: 'every month on the 5th day or 2nd to the last day' or
    // it may be confusing like 'every month on the 5th or 2nd to the last day'
    'ordinal_number' => function ($str, $params) {
        $number = $params['number'];

        $ends = array('a', 'a', 'nd', 'a', 'a', 'a', 'a', 'a', 'a', 'a');
        $suffix = '';

        $isNegative = $number < 0;

        if ($number == -1) {
            $abbreviation = 'último';
        } else {
            if ($isNegative) {
                $number = abs($number);
                $suffix = ' a la última';
            }

            if (($number % 100) >= 11 && ($number % 100) <= 13) {
                $abbreviation = $number.'a';
            } else {
                $abbreviation = $number.$ends[$number % 10];
            }
        }

        if (!empty($params['has_negatives'])) {
            $suffix .= ' día';
        }

        return $abbreviation . $suffix;
    },
);
