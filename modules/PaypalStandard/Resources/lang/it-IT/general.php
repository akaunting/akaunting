<?php

return [

    'name'              => 'PayPal Standard',
    'description'       => 'Abilita l\'opzione di pagamento standard di PayPal',

    'form' => [
        'email'         => 'Email',
        'mode'          => 'Modalità',
        'debug'         => 'Debug',
        'transaction'   => 'Transazione',
        'customer'      => 'Mostra al Cliente',
        'order'         => 'Ordine',
    ],

    'test_mode'         => 'Avviso: il gateway di pagamento è in "Modalità sandbox". Il tuo account non verrà addebitato.',
    //'description'       => 'Pay with PAYPAL',

];
