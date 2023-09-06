<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'Κυριακή',
    'Δευτέρα',
    'Τρίτη',
    'Τετάρτη',
    'Πέμπτη',
    'Παρασκευή',
    'Σάββατο',
);
$months = array(
    'Ιανουάριος',
    'Φεβρουάριος',
    'Μάρτιος',
    'Απρίλιος',
    'Μάιος',
    'Ιούνιος',
    'Ιούλιος',
    'Αύγουστος',
    'Σεπτέμβριος',
    'Οκτώβριος',
    'Νοέμβριος',
    'Δεκέμβριος',
);
$months_genitive = array(
    'Ιανουαρίου',
    'Φεβρουαρίου',
    'Μαρτίου',
    'Απριλίου',
    'Μαΐου',
    'Ιουνίου',
    'Ιουλίου',
    'Αυγούστου',
    'Σεπτεμβρίου',
    'Οκτωβρίου',
    'Νοεμβρίου',
    'Δεκεμβρίου',
);

return array(
    'Unable to fully convert this rrule to text.' => 'Αδυναμία πλήρους μετατροπής αυτού του κανόνα rrule σε κείμενο.',
    'for %count% times' => 'για %count% φορές',
    'for one time' => 'για μία φορά',
    '(~ approximate)' => '(~ κατά προσέγγιση)',
    'until %date%' => 'μέχρι %date%', // e.g. every year until July 4, 2014
    'day_date' => function ($str, $params) use ($days, $months_genitive) { // outputs a day date, e.g. 4 Ιουλίου 2014
        return date('j', $params['date']) . ' ' . $months_genitive[date('n', $params['date']) - 1] . ' '. date('Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months_genitive) { // outputs a day month, e.g. 4 Ιουλίου
        return $params['day'] . ' ' . $months_genitive[$params['month'] - 1];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 'και',
    'or' => 'ή',
    'in_month' => 'τον', // e.g. weekly in January, May and August
    'in_week' => 'την', // e.g. yearly in week 3
    'on' => 'την', // e.g. every day on Tuesday, Wednesday and Friday
    'the_for_monthday' => 'την', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => 'την', // e.g. monthly on the 4th Monday
    'on the' => 'την', // e.g. every year on the 1st and 200th day
    'of_the_month' => 'του μήνα', // e.g. every year on the 2nd or 3rd of the month
    'every %count% years' => 'κάθε %count% χρόνια',
    'every year' => 'ετήσια',
    'every_month_list' => 'κάθε', // e.g. every January, May and August
    'every %count% months' => 'κάθε %count% μήνες',
    'every month' => 'μηνιαία',
    'every %count% weeks' => 'κάθε %count% εβδομάδες',
    'every week' => 'εβδομαδιαία',
    'every %count% days' => 'κάθε %count% ημέρες',
    'every day' => 'καθημερινά',
    'last' => 'τελευταία', // e.g. 2nd last Friday
    'days' => 'ημέρες',
    'day' => 'ημέρα',
    'weeks' => 'εβδομάδες',
    'week' => 'εβδομάδα',
    // formats a number with a prefix e.g. every year on the 1st and 200th day
    // negative numbers should be handled as in '5th to the last' or 'last'
    //
    // if has_negatives is true in the params, it is good form to add 'day' after
    // each number, as in: 'every month on the 5th day or 2nd to the last day' or
    // it may be confusing like 'every month on the 5th or 2nd to the last day'
    'ordinal_number' => function ($str, $params) {
        $number = $params['number'];

        $ends = 'η';
        $suffix = '';

        $isNegative = $number < 0;

        if ($number == -1) {
            $abbreviation = 'τελευταία';
        } else {
            if ($isNegative) {
                $number = abs($number);
                $suffix = ' μέχρι την τελευταία';
            }

            $abbreviation = $number . $ends;
        }

        if (!empty($params['has_negatives'])) {
            $suffix .= ' ημέρα';
        }

        return $abbreviation . $suffix;
    },
);
