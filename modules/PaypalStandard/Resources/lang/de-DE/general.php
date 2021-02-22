<?php

return [

    'name'              => 'PayPal Standard',
    'description'       => 'Aktivieren Sie die Standard-Zahlungsoption von PayPal',

    'form' => [
        'email'         => 'E-Mail',
        'mode'          => 'Modus',
        'debug'         => 'Debug',
        'transaction'   => 'Transaktion',
        'customer'      => 'Dem Kunden zeigen',
        'order'         => 'Bestellung',
    ],

    'test_mode'         => 'Warnung: Das Payment-Gateway befindet sich im <b>Sandbox-Modus</b>. Ihr Konto wird nicht belastet.',
    //'description'       => 'Pay with PAYPAL',

];
