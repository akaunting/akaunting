<?php

return [

    'bill_number'           => 'Nomor Tagihan',
    'bill_date'             => 'Tanggal Tagihan',
    'total_price'           => 'Total Harga',
    'due_date'              => 'Tanggal Jatuh Tempo',
    'order_number'          => 'Nomor Pesanan',
    'bill_from'             => 'Tagihan Dari',

    'quantity'              => 'Jumlah',
    'price'                 => 'Harga',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Diskon',
    'item_discount'         => 'Potongan Harga',
    'tax_total'             => 'Total Pajak',
    'total'                 => 'Total',

    'item_name'             => 'Nama Barang | Nama Barang',

    'show_discount'         => 'Diskon :discount%',
    'add_discount'          => 'Tambahkan diskon',
    'discount_desc'         => 'dari sub-total',

    'payment_due'           => 'Pembayaran Jatuh Tempo',
    'amount_due'            => 'Jumlah Jatuh Tempo',
    'paid'                  => 'Dibayar',
    'histories'             => 'Riwayat',
    'payments'              => 'Pembayaran',
    'add_payment'           => 'Tambahkan Pembayaran',
    'mark_paid'             => 'Tandai sudah dibayar',
    'mark_received'         => 'Tandai Diterima',
    'mark_cancelled'        => 'Dibatalkan',
    'download_pdf'          => 'Unduh PDF',
    'send_mail'             => 'Kirim Email',
    'create_bill'           => 'Membuat Tagihan',
    'receive_bill'          => 'Menerima Tagihan',
    'make_payment'          => 'Melakukan Pembayaran',

    'statuses' => [
        'draft'             => 'Konsep',
        'received'          => 'Diterima',
        'partial'           => 'Sebagian',
        'paid'              => 'Dibayar',
        'overdue'           => 'Jatuh Tempo',
        'unpaid'            => 'Belum Dibayar',
        'cancelled'         => 'Dibatalkan',
    ],

    'messages' => [
        'marked_received'   => 'Tagihan sudah diterima',
        'marked_paid'       => 'Tagihan sudah dibayar',
        'marked_cancelled'  => 'Tagihan dibatalkan',
        'draft'             => 'Ini adalah <b>DRAFT</b> tagihan dan akan terlihat di grafik setelah dibayarkan.',

        'status' => [
            'created'       => 'Dibuat pada:date',
            'receive' => [
                'draft'     => 'Tidak terkirim',
                'received'  => 'Diterima pada:date',
            ],
            'paid' => [
                'await'     => 'Menunggu pembayaran',
            ],
        ],
    ],

];
