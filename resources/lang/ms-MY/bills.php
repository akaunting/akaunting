<?php

return [

    'bill_number'           => 'Nombor Bil',
    'bill_date'             => 'Tarikh Bil',
    'total_price'           => 'Jumlah Harga',
    'due_date'              => 'Tarikh Matang',
    'order_number'          => 'Nombor Pesanan',
    'bill_from'             => 'Bil Dari',

    'quantity'              => 'Kuantiti',
    'price'                 => 'Harga',
    'sub_total'             => 'Subjumlah',
    'discount'              => 'Diskaun',
    'tax_total'             => 'Jumlah Cukai',
    'total'                 => 'Jumlah',

    'item_name'             => 'Nama Item|Nama-nama Item',

    'show_discount'         => 'Diskaun :discount%',
    'add_discount'          => 'Tambah Diskaun',
    'discount_desc'         => 'dari subjumlah',

    'payment_due'           => 'Bayaran yang perlu dibayar',
    'amount_due'            => 'Amaun perlu dibayar',
    'paid'                  => 'Telah dibayar',
    'histories'             => 'Maklumat lampau',
    'payments'              => 'Bayaran',
    'add_payment'           => 'Tambah Bayaran',
    'mark_received'         => 'Tanda Diterima',
    'download_pdf'          => 'Muat Turun PDF',
    'send_mail'             => 'Hantar Emel',
    'create_bill'           => 'Cipta Bil',
    'receive_bill'          => 'Terima Bil',
    'make_payment'          => 'Buat Pembayaran',

    'statuses' => [
        'draft'             => 'Draf',
        'received'          => 'Diterima',
        'partial'           => 'Sebahagian',
        'paid'              => 'Dibayar',
        'overdue'           => 'Tertunggak',
        'unpaid'            => 'Belum Dibayar',
    ],

    'messages' => [
        'received'          => 'Bil ditanda sebagai selamat diterima!',
        'draft'             => 'Ini adalah bil <b>DERAF</b> dan akan dikemaskini ke carta setelah ia diterima.',

        'status' => [
            'created'       => 'Dicipta pada :date',
            'receive' => [
                'draft'     => 'Tidak dihantar',
                'received'  => 'Diterima pada :date',
            ],
            'paid' => [
                'await'     => 'Menunggu pembayaran',
            ],
        ],
    ],

];
