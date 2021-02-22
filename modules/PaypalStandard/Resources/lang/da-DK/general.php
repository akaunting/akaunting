<?php

return [

    'name'              => 'PayPal Standard',
    'description'       => 'Aktivér standard betalingsmulighed for PayPal',

    'form' => [
        'email'         => 'E-mail',
        'mode'          => 'Mode',
        'debug'         => 'Fejlfinde',
        'transaction'   => 'Transaktion',
        'customer'      => 'Vis til kunde',
        'order'         => 'Bestille',
    ],

    'test_mode'         => 'Advarsel: Betalingsgatewayen er i \'Sandbox Mode\'. Din konto bliver ikke debiteret.',
    //'description'       => 'Pay with PAYPAL',

];
