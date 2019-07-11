<?php

return [

    'invoice_number'        => 'Nomor faktur',
    'invoice_date'          => 'Tanggal faktur',
    'total_price'           => 'Total harga',
    'due_date'              => 'Batas tanggal terakhir',
    'order_number'          => 'Jumlah Pesanan',
    'bill_to'               => 'Pembayaran Kepada',

    'quantity'              => 'Kuantitas',
    'price'                 => 'Harga',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Diskon',
    'tax_total'             => 'Total Pajak',
    'total'                 => 'Total',

    'item_name'             => 'Nama Item | Nama Item',

    'show_discount'         => 'Diskon :discount%',
    'add_discount'          => 'Tambahkan diskon',
    'discount_desc'         => 'of subtotal',

    'payment_due'           => 'Tanggal Pembayaran',
    'paid'                  => 'Dibayar',
    'histories'             => 'Sejarah',
    'payments'              => 'Pembayaran',
    'add_payment'           => 'Tambahkan Pembayaran',
    'mark_paid'             => 'Ditandai Dibayar',
    'mark_sent'             => 'Tandai Dikirim',
    'download_pdf'          => 'Unduh PDF',
    'send_mail'             => 'Kirim Email',
    'all_invoices'          => 'Login to view all invoices',
    'create_invoice'        => 'Create Invoice',
    'send_invoice'          => 'Send Invoice',
    'get_paid'              => 'Get Paid',
    'accept_payments'       => 'Accept Online Payments',

    'status' => [
        'draft'             => 'Konsep',
        'sent'              => 'Mengirim',
        'viewed'            => 'Lihat',
        'approved'          => 'Disetujui',
        'partial'           => 'Sebagian',
        'paid'              => 'Dibayar',
    ],

    'messages' => [
        'email_sent'        => 'Email faktur telah berhasil dikirim!',
        'marked_sent'       => 'Faktur ditandai sebagai berhasil dikirim!',
        'email_required'    => 'Tidak ada alamat email untuk pelanggan ini!',
        'draft'             => 'This is a <b>DRAFT</b> invoice and will be reflected to charts after it gets sent.',

        'status' => [
            'created'       => 'Created on :date',
            'send' => [
                'draft'     => 'Not sent',
                'sent'      => 'Sent on :date',
            ],
            'paid' => [
                'await'     => 'Awaiting payment',
            ],
        ],
    ],

    'notification' => [
        'message'           => 'Anda menerima email ini karena Anda memiliki faktur jumlah yang akan datang: pelanggan pelanggan.',
        'button'            => 'Bayar sekarang',
    ],

];
