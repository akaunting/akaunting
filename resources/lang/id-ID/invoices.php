<?php

return [

    'invoice_number'        => 'Nomor faktur',
    'invoice_date'          => 'Tanggal faktur',
    'invoice_amount'        => 'Jumlah Faktur',
    'total_price'           => 'Total harga',
    'due_date'              => 'Tanggal Jatuh Tempo',
    'order_number'          => 'Jumlah Pesanan',
    'bill_to'               => 'Tagihan Kepada',
    'cancel_date'           => 'Batalkan Tanggal',

    'quantity'              => 'Kuantitas',
    'price'                 => 'Harga',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Diskon',
    'item_discount'         => 'Potongan Harga',
    'tax_total'             => 'Total Pajak',
    'total'                 => 'Total',

    'item_name'             => 'Nama Item|Nama Item',
    'recurring_invoices'    => 'Faktur Berulang|Faktur Berulang',

    'show_discount'         => 'Diskon :discount%',
    'add_discount'          => 'Tambahkan diskon',
    'discount_desc'         => 'Dari subtotal',

    'payment_due'           => 'Tanggal Pembayaran',
    'paid'                  => 'Dibayar',
    'histories'             => 'Riwayat',
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
    'payments_received'     => 'Pembayaran diterima',

    'form_description' => [
        'billing'           => 'Detail penagihan muncul di faktur Anda. Tanggal Faktur digunakan di dasbor dan laporan. Pilih tanggal yang Anda harapkan untuk dibayar sebagai Tanggal Jatuh Tempo.',
    ],

    'messages' => [
        'email_required'    => 'Tidak ada alamat email untuk pelanggan ini!',
        'totals_required'   => 'Total faktur diperlukan, mohon edit :type dan coba simpan kembali.',

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

        'name_or_description_required' => 'Faktur Anda harus menunjukkan setidaknya <b>:name</b> atau <b>:decription</b>',
    ],

    'slider' => [
        'create'            => ':user membuat faktur ini pada :date',
        'create_recurring'  => ':user membuat template berulang ini pada :date',
        'schedule'          => 'Ulangi setiap :interval :frekuensi sejak :tanggal',
        'children'          => ':count faktur telah berhasil dibuat secara otomatis',
    ],

    'share' => [
        'show_link'         => 'Pelanggan Anda dapat melihat faktur di tautan ini',
        'copy_link'         => 'Salin tautan dan bagikan dengan pelanggan Anda.',
        'success_message'   => 'Tautan berbagi disalin ke papan klip!',
    ],

    'sticky' => [
        'description'       => 'Anda sedang mempratinjau bagaimana pelanggan Anda akan melihat versi web faktur Anda.',
    ],

];
