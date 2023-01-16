<?php

return [

    'invoice_number'        => 'Nombor Invois',
    'invoice_date'          => 'Tarikh Invois',
    'total_price'           => 'Jumlah Harga',
    'due_date'              => 'Tarikh Matang',
    'order_number'          => 'Nombor Pesanan',
    'bill_to'               => 'Bil Kepada',

    'quantity'              => 'Kuantiti',
    'price'                 => 'Harga',
    'sub_total'             => 'Subjumlah',
    'discount'              => 'Diskaun',
    'tax_total'             => 'Jumlah Cukai',
    'total'                 => 'Jumlah',

    'item_name'             => 'Nama Item|Nama Item',

    'show_discount'         => 'Diskaun :discount%',
    'add_discount'          => 'Tambah Diskaun',
    'discount_desc'         => 'dari subjumlah',

    'payment_due'           => 'Tempoh Tamat',
    'paid'                  => 'Telah Dibayar',
    'histories'             => 'Maklumat Lalu',
    'payments'              => 'Pembayaran',
    'add_payment'           => 'Tambah Pembayaran',
    'mark_paid'             => 'Tanda Dibayar',
    'mark_sent'             => 'Sudah Dihantar',
    'mark_viewed'           => 'Tandakan telah dilihat',
    'download_pdf'          => 'Muat Turun PDF',
    'send_mail'             => 'Hantar Emel',
    'all_invoices'          => 'Log masuk untuk lihat semua invois',
    'create_invoice'        => 'Cipta Invois',
    'send_invoice'          => 'Hantar Invois',
    'get_paid'              => 'Dapatkan Bayaran',
    'accept_payments'       => 'Terima Bayaran Secara Dalam Talian',

    'statuses' => [
        'draft'             => 'Draf',
        'sent'              => 'Telah dihantar',
        'viewed'            => 'Telah Dilihat',
        'approved'          => 'Diluluskan',
        'partial'           => 'Sebahagian',
        'paid'              => 'Telah dbayar',
        'overdue'           => 'Tertunggak',
        'unpaid'            => 'Belum Dibayar',
    ],

    'messages' => [
        'email_sent'        => 'Emel invois telah dihantar!',
        'marked_sent'       => 'Invois ditandakan sebagai telah dihantar!',
        'marked_paid'       => 'Invois ditandakan sebagai telah dibayar!',
        'email_required'    => 'Tiada alamat emel untuk pelanggan ini!',
        'draft'             => 'Ini adalah <b>DRAF</b> invois dan akan dikemaskini ke carta setelah ia dihantar.',

        'status' => [
            'created'       => 'Dihasilkan pada :date',
            'viewed'        => 'Telah Dilihat',
            'send' => [
                'draft'     => 'Tidak dihantar',
                'sent'      => 'Dihantar pada :date',
            ],
            'paid' => [
                'await'     => 'Menunggu pembayaran',
            ],
        ],
    ],

];
