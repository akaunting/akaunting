<?php

return [

    'invoice_number'        => 'Nomor faktur',
    'invoice_date'          => 'Tanggal faktur',
    'total_price'           => 'Total harga',
    'due_date'              => 'Batas tanggal terakhir',
    'order_number'          => 'Jumlah Pesanan',
    'bill_to'               => 'Tagihan Kepada',

    'quantity'              => 'Kuantitas',
    'price'                 => 'Harga',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Diskon',
    'item_discount'         => 'Potongan Harga',
    'tax_total'             => 'Total Pajak',
    'total'                 => 'Total',

    'item_name'             => 'Nama Item | Nama Item',

    'show_discount'         => 'Diskon :discount%',
    'add_discount'          => 'Tambahkan diskon',
    'discount_desc'         => 'Dari subtotal',

    'payment_due'           => 'Tanggal Pembayaran',
    'paid'                  => 'Dibayar',
    'histories'             => 'Sejarah',
    'payments'              => 'Pembayaran',
    'add_payment'           => 'Tambahkan Pembayaran',
    'mark_paid'             => 'Ditandai Dibayar',
    'mark_sent'             => 'Tandai Dikirim',
    'mark_viewed'           => 'Tandai Dilihat',
    'mark_cancelled'        => 'Tandai dibatalkan',
    'download_pdf'          => 'Unduh PDF',
    'send_mail'             => 'Kirim Email',
    'all_invoices'          => 'Masuk untuk melihat seluruh faktur',
    'create_invoice'        => 'Buat faktur',
    'send_invoice'          => 'Kirim faktur',
    'get_paid'              => 'Telah dibayar',
    'accept_payments'       => 'Menerima pembayaran online',

    'statuses' => [
        'draft'             => 'Konsep',
        'sent'              => 'Terkirim',
        'viewed'            => 'Dilihat',
        'approved'          => 'Disetujui',
        'partial'           => 'Sebagian',
        'paid'              => 'Dibayar',
        'overdue'           => 'Jatuh Tempo',
        'unpaid'            => 'Belum Dibayar',
        'cancelled'         => 'Dibatalkan',
    ],

    'messages' => [
        'email_sent'        => 'Email faktur telah dikirim!',
        'marked_sent'       => 'Faktur ditandai sebagai terkirim!',
        'marked_paid'       => 'Faktur ditandai sebagai berbayar!',
        'marked_viewed'     => 'Tagihan ditandai sudah dilihat',
        'marked_cancelled'  => 'Tagihan ditandai sudah dibatalkan',
        'email_required'    => 'Tidak ada alamat email untuk pelanggan ini!',
        'draft'             => 'Faktur ini merupakan <b>DRAFT</b> dan akan terlihat pada grafik ketika sudah dibayarkan',

        'status' => [
            'created'       => 'Dibuat pada :date',
            'viewed'        => 'Dilihat',
            'send' => [
                'draft'     => 'Tidak terkirim',
                'sent'      => 'Terkirim pada :date',
            ],
            'paid' => [
                'await'     => 'Menunggu pembayaran',
            ],
        ],
    ],

];
