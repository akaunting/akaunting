<?php

return [

    'name'              => 'PayPal Standard',
    'description'       => 'Aktifkan opsi pembayaran standar PayPal',

    'form' => [
        'email'         => 'Email',
        'mode'          => 'Mode',
        'debug'         => 'Debug',
        'transaction'   => 'Transaksi',
        'customer'      => 'Tunjukkan kepada Pelanggan',
        'order'         => 'Pesanan',
    ],

    'test_mode'         => 'Peringatan: Gateway pembayaran dalam \'Mode Sandbox\'. Akun Anda tidak akan dikenakan biaya.',
    //'description'       => 'Pay with PAYPAL',

];
