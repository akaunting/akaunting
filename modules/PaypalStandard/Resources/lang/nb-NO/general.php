<?php

return [

    'name'              => 'PayPal-standard',
    'description'       => 'Aktiver standard betalingsalternativ for PayPal',

    'form' => [
        'email'         => 'E-post',
        'mode'          => 'Modus',
        'debug'         => 'Debug',
        'transaction'   => 'Transaksjon',
        'customer'      => 'Vis til kunde',
        'order'         => 'Rekkefølge',
    ],

    'test_mode'         => 'Advarsel: Betalingsporten er i \'Sandbox Mode\'. Kontoen din blir ikke belastet.',
    //'description'       => 'Pay with PAYPAL',

];
