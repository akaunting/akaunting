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
    'download_pdf'          => 'Unduh PDF',
    'send_mail'             => 'Kirim Email',
    'all_invoices'          => 'Masuk untuk melihat seluruh faktur',
    'create_invoice'        => 'Buat faktur',
    'send_invoice'          => 'Kirim faktur',
    'get_paid'              => 'Telah dibayar',
    'accept_payments'       => 'Menerima pembayaran online',

    'statuses' => [
        'draft'             => 'Konsep',
        'sent'              => 'Mengirim',
        'viewed'            => 'Lihat',
        'approved'          => 'Disetujui',
        'partial'           => 'Sebagian',
        'paid'              => 'Dibayar',
    ],

    'messages' => [
        'email_sent'        => 'Invoice email has been sent!',
        'marked_sent'       => 'Invoice marked as sent!',
        'marked_paid'       => 'Invoice marked as paid!',
        'email_required'    => 'Tidak ada alamat email untuk pelanggan ini!',
        'draft'             => 'Faktur ini merupakan <b>DRAFT</b> dan akan terlihat pada grafik ketika sudah dibayarkan',

        'status' => [
            'created'       => 'Dibuat pada :date',
            'viewed'        => 'Viewed',
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
