<?php

return [

    'invoice_number'        => 'Nombor Invois',
    'invoice_date'          => 'Tarikh Invois',
    'invoice_amount'        => 'Jumlah Invois',
    'total_price'           => 'Jumlah Harga',
    'due_date'              => 'Tarikh Jatuh Tempo',
    'order_number'          => 'Nombor Pesanan',
    'bill_to'               => 'Dibilkan Kepada',
    'cancel_date'           => 'Tarikh Pembatalan',

    'quantity'              => 'Kuantiti',
    'price'                 => 'Harga',
    'sub_total'             => 'Jumlah Kecil',
    'discount'              => 'Diskaun',
    'item_discount'         => 'Diskaun Baris',
    'tax_total'             => 'Jumlah Cukai',
    'total'                 => 'Jumlah',

    'item_name'             => 'Nama Item|Nama Item',
    'recurring_invoices'    => 'Invois Berulang|Invois Berulang',

    'show_discount'         => 'Diskaun :discount%',
    'add_discount'          => 'Tambah Diskaun',
    'discount_desc'         => 'daripada jumlah kecil',

    'payment_due'           => 'Bayaran Tertunggak',
    'paid'                  => 'Dibayar',
    'histories'             => 'Sejarah',
    'payments'              => 'Bayaran',
    'add_payment'           => 'Tambah Bayaran',
    'mark_paid'             => 'Tanda Dibayar',
    'mark_sent'             => 'Tanda Dihantar',
    'mark_viewed'           => 'Tanda Dilihat',
    'mark_cancelled'        => 'Tanda Dibatalkan',
    'download_pdf'          => 'Muat Turun PDF',
    'send_mail'             => 'Hantar E-mel',
    'all_invoices'          => 'Log masuk untuk melihat semua invois',
    'create_invoice'        => 'Cipta Invois',
    'send_invoice'          => 'Hantar Invois',
    'get_paid'              => 'Terima Bayaran',
    'accept_payments'       => 'Terima Bayaran Dalam Talian',
    'payments_received'     => 'Bayaran diterima',
    'over_payment'          => 'Jumlah yang anda masukkan melebihi jumlah keseluruhan: :amount',

    'form_description' => [
        'billing'           => 'Butiran pengebilan muncul dalam invois anda. Tarikh Invois digunakan dalam papan pemuka dan laporan. Pilih tarikh yang anda jangka akan dibayar sebagai Tarikh Jatuh Tempo.',
    ],

    'messages' => [
        'email_required'    => 'Tiada alamat e-mel untuk pelanggan ini!',
        'totals_required'   => 'Jumlah invois diperlukan. Sila sunting :type dan simpan semula.',

        'draft'             => 'Invois ini ialah <b>DRAF</b> dan akan dipaparkan pada carta selepas ia dihantar.',

        'status' => [
            'created'       => 'Dicipta pada :date',
            'viewed'        => 'Dilihat',
            'send' => [
                'draft'     => 'Tidak dihantar',
                'sent'      => 'Dihantar pada :date',
            ],
            'paid' => [
                'await'     => 'Menunggu bayaran',
            ],
        ],

        'name_or_description_required' => 'Invois anda mesti memaparkan sekurang-kurangnya salah satu daripada <b>:name</b> atau <b>:description</b>.',
    ],

    'share' => [
        'show_link'         => 'Pelanggan anda boleh melihat invois di pautan ini',
        'copy_link'         => 'Salin pautan dan kongsi dengan pelanggan anda.',
        'success_message'   => 'Pautan kongsi disalin ke papan keratan!',
    ],

    'sticky' => [
        'description'       => 'Anda sedang pratonton cara pelanggan anda akan melihat versi web invois anda.',
    ],

];
