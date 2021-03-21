<?php

return [

    'name'              => 'PayPalスタンダード',
    'description'       => 'PayPalの標準支払オプションを有効にします',

    'form' => [
        'email'         => 'Eメール',
        'mode'          => 'モード',
        'debug'         => 'デバッグ',
        'transaction'   => 'トランザクション',
        'customer'      => '顧客に示す',
        'order'         => 'オーダー',
    ],

    'test_mode'         => '警告: 支払いゲートウェイは \'サンドボックスモード\'です。 アカウントには請求されません。 ',
    //'description'       => 'Pay with PAYPAL',

];
