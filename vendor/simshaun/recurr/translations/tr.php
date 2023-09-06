<?php

// sunday first as date('w') is zero-based on sunday
$days = array(
    'Pazar',
    'Pazartesi',
    'Salı',
    'Çarşamba',
    'Perşembe',
    'Cuma',
    'Cumartesi',
);
$months = array(
    'Ocak',
    'Şubat',
    'Mart',
    'Nisan',
    'Mayıs',
    'Haziran',
    'Temmuz',
    'Ağustos',
    'Eylül',
    'Ekim',
    'Kasım',
    'Aralık',
);

return array(
    'Unable to fully convert this rrule to text.' => 'Bu rrule tam metne dönüştürülemiyor.',
    'for %count% times' => '%count% kez',
    'for one time' => 'bir kere',
    '(~ approximate)' => '(~ yaklaşık)',
    'until %date%' => 'kadar %date%', // e.g. 4 Temmuz 2014 e kadar her yıl
    'day_date' => function ($str, $params) use ($days, $months) { // tarih çıktıları, e.g. Temmuz 4, 2014
        return $months[date('n', $params['date']) - 1] . ' '. date('j, Y', $params['date']);
    },
    'day_month' => function ($str, $params) use ($days, $months) { // outputs a day month, e.g. July 4
        return $months[$params['month'] - 1].' '.$params['day'];
    },
    'day_names' => $days,
    'month_names' => $months,
    'and' => 've',
    'or' => 'veya',
    'in_month' => 'içinde', // e.g. Ocak, Mayıs ve Ağustos'ta haftalık
    'in_week' => 'içinde', // e.g. yıllık haftada 3
    'on' => 'on', // e.g. her Salı, Çarşamba ve Cuma günü
    'the_for_monthday' => 'the', // e.g. monthly on Tuesday the 1st
    'the_for_weekday' => 'the', // e.g. monthly on the 4th Monday
    'on the' => 'üzerinde', // e.g. her yıl 1. ve 200. günde
    'of_the_month' => 'ayın', // e.g. her yıl 2. ve 3. ayın
    'every %count% years' => 'every %count% years',
    'every year' => 'yıllık',
    'every_month_list' => 'her', // e.g. her Ocak, Mayıs ve Ağustos
    'every %count% months' => 'her %count% ay',
    'every month' => 'aylık',
    'every %count% weeks' => 'her %count% hafta',
    'every week' => 'haftalık',
    'every %count% days' => 'her %count% gün',
    'every day' => 'günlük',
    'last' => 'son', // e.g. 2nd last Friday
    'days' => 'günler',
    'day' => 'gün',
    'weeks' => 'haftalar',
    'week' => 'hafta',
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
            $abbreviation = 'son';
        } else {
            if ($isNegative) {
                $number = abs($number);
                $suffix = ' sonuna kadar';
            }

            if (($number % 100) >= 11 && ($number % 100) <= 13) {
                $abbreviation = $number.'th';
            } else {
                $abbreviation = $number.$ends[$number % 10];
            }
        }

        if (!empty($params['has_negatives'])) {
            $suffix .= ' gün';
        }

        return $abbreviation . $suffix;
    },
);
