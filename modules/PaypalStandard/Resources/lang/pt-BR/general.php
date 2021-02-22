<?php

return [

    'name'              => 'PayPal Standard',
    'description'       => 'Habilitar a opção de pagamento padrão do PayPal',

    'form' => [
        'email'         => 'E-mail',
        'mode'          => 'Modo',
        'debug'         => 'Depuração',
        'transaction'   => 'Transação',
        'customer'      => 'Mostrar ao Cliente',
        'order'         => 'Ordem',
    ],

    'test_mode'         => 'Aviso: O gateway de pagamento está no \'Modo seguro\'. Sua conta não será cobrada.',
    //'description'       => 'Pay with PAYPAL',

];
