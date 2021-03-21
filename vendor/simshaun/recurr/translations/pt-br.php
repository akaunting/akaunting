<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'domingo',
    'segunda-feira',
    'terça-feira',
    'quarta-feira',
    'quinta-feira',
    'sexta-feira',
    'sábado',
);
$months = array(
    'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro',
);

return array(
    'Unable to fully convert this rrule to text.' => 'Não foi possível converter esta regra para texto.',
    'for %count% times' => 'por %count% vezes',
    'for one time' => 'uma vez',
    '(~ approximate)' => '(~ approximado)',
    'until %date%' => 'até %date%', // e.g. every year until July 4, 2014
    'day_date' => function ($str, $params) use ($days, $months) { // outputs a day date, e.g. July 4, 2014
        return date('j', $params['date']) . ' de ' . $months[date('n', $params['date']) - 1] . ' de ' . date('Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months) { // outputs a day month, e.g. July 4
        return $params['day'].' de '.$months[$params['month'] - 1];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 'e',
    'or' => 'ou',
    'in_month' => 'em', // e.g. weekly in January, May and August
    'in_week' => 'na', // e.g. yearly in week 3
    'on' => 'à', // e.g. every day on Tuesday, Wednesday and Friday
    'the_for_monthday' => 'o', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => 'o', // e.g. monthly on the 4th Monday
    'on the' => 'no', // e.g. every year on the 1st and 200th day
    'of_the_month' => 'do mês', // e.g. every year on the 2nd or 3rd of the month
    'every %count% years' => 'a cada %count% anos',
    'every year' => 'anualmente',
    'every_month_list' => 'sempre em', // e.g. every January, May and August
    'every %count% months' => 'a cada %count% meses',
    'every month' => 'mensalmente',
    'every %count% weeks' => 'a cada %count% semanas',
    'every week' => 'semanalmente',
    'every %count% days' => 'a cada %count% dias',
    'every day' => 'diariamente',
    'last' => 'último', // e.g. 2nd last Friday
    'days' => 'dias',
    'day' => 'dia',
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

        $abbreviation = $number.'°';
        $isNegative = $number < 0;
        if ($isNegative) {
            $abbreviation = $abbreviation.' último';
        }

        $suffix = '';
        if (!empty($params['has_negatives'])) {
            $suffix .= ' dia';
        }

        return $abbreviation . $suffix;
    },
);
