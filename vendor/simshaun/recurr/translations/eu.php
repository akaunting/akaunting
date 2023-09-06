<?php

return array(
    'Unable to fully convert this rrule to text.' => 'Ezin izan da rrule testura osoki bihurtu.',
    'for %count% times' => '%count% aldiz',
    'for %count% time' => '%count% aldia',
    '(~ approximate)' => '(~ inguru)',
    'until %date%' => '%date% arte', // e.g. every year until July 4, 2014
    'day_date' => defined('PHP_WINDOWS_VERSION_BUILD') ? '%B %#d, %Y' : '%B %e, %Y',
    'and' => 'eta',
    'or' => 'edo',
    'in' => 'hilabete hauetan:', // e.g. every week in January, May and August
    'on' => 'egun hauetan:', // e.g. every day on Tuesday, Wednesday and Friday
    'the' => '',
    'on the' => '', // e.g. every year on the 1st and 200th day
    'every %count% years' => '%count% urtero',
    'every year' => 'urtero',
    'every_month_list' => 'hilabete hauetan', // e.g. every January, May and August
    'every %count% months' => '%count% hilabetero',
    'every month' => 'hilabetero',
    'every %count% weeks' => '%count% astero',
    'every week' => 'astero',
    'every %count% days' => '%count% egunero',
    'every day' => 'egunero',
    'last' => 'azken', // e.g. 2nd last Friday
    'days' => 'egun',
    'day' => 'egun',
    'weeks' => 'aste',
    'week' => 'aste',
    'ordinal_number' => function ($str, $params) { // formats a number with a prefix e.g. every year on the 1st and 200th day
        $number = $params['number'];

        $ends = array('garren', 'go', 'garren', 'garren', 'garren', 'garren', 'garren', 'garren', 'garren', 'garren');

        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            $abbreviation = $number.'garren';
        } else {
            $abbreviation = $number.$ends[$number % 10];
        }

        return $abbreviation;
    },
);
