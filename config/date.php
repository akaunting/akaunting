<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Formats
    |--------------------------------------------------------------------------
    |
    | The date formats a company can pick in the localisation settings. Dates
    | are shown and saved only in these, whatever else is stored.
    |
    */

    'formats' => ['d M Y', 'd F Y', 'd m Y', 'm d Y', 'Y m d'],

    /*
    |--------------------------------------------------------------------------
    | Separators
    |--------------------------------------------------------------------------
    |
    | The separators a company can pick, with the character each one puts
    | between the day, the month and the year.
    |
    */

    'separators' => [
        'dash'  => '-',
        'slash' => '/',
        'dot'   => '.',
        'comma' => ',',
        'space' => ' ',
    ],

];
