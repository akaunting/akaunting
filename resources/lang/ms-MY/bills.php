<?php

return [

    'bill_number'           => 'Nomor Bil',
    'bill_date'             => 'Tanggal Bil',
    'bill_amount'           => 'Jumlah Bil',
    'total_price'           => 'Total Harga',
    'due_date'              => 'Tanggal Jatuh Tempo',
    'order_number'          => 'Nomor Pesanan',
    'bill_from'             => 'Bil Dari',

    'quantity'              => 'Jumlah',
    'price'                 => 'Harga',
    'sub_total'             => 'Subtotal',
    'discount'              => 'Diskon',
    'item_discount'         => 'Potongan Harga',
    'tax_total'             => 'Total Pajak',
    'total'                 => 'Total',

    'item_name'             => 'Nama Item|Nama Item',
    'recurring_bills'       => 'Bil Rutin|Bil Rutin',

    'show_discount'         => 'Diskon :discount%',
    'add_discount'          => 'Tambahkan diskon',
    'discount_desc'         => 'dari sub-total',

    'payment_made'          => 'Bayaran Dibuat',
    'payment_due'           => 'Bayaran Jatuh Tempo',
    'amount_due'            => 'Jumlah Jatuh Tempo',
    'paid'                  => 'Dibayar',
    'histories'             => 'Riwayat',
    'payments'              => 'Bayaran',
    'add_payment'           => 'Tambahkan Bayaran',
    'mark_paid'             => 'Tandai sudah dibayar',
    'mark_received'         => 'Tandai Diterima',
    'mark_cancelled'        => 'Dibatalkan',
    'download_pdf'          => 'Unduh PDF',
    'send_mail'             => 'Kirim Email',
    'create_bill'           => 'Membuat Bil',
    'receive_bill'          => 'Menerima Bil',
    'make_payment'          => 'Melakukan Bayaran',

    'form_description' => [
        'billing'           => 'Detail penagihan muncul di Bil Anda. Tanggal Bil digunakan di dasbor dan laporan. Pilih tanggal yang Anda harapkan untuk membayar sebagai Tanggal Jatuh Tempo.',
    ],

    'messages' => [
        'draft'             => 'Ini adalah <b>DRAFT</b> Bil dan akan terlihat di grafik setelah dibayarkan.',

        'status' => [
            'created'       => 'Dibuat pada :date',
            'receive' => [
                'draft'     => 'Tidak terkirim',
                'received'  => 'Diterima pada :date',
            ],
            'paid' => [
                'await'     => 'Menunggu bayaran',
            ],
        ],
    ],

];
