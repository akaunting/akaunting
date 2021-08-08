<?php

return [

    'company' => [
        'description'                => 'Ubah nama perusahaan, email, alamat, NPWP dll',
        'name'                       => 'Nama',
        'email'                      => 'Email',
        'phone'                      => 'Telepon',
        'address'                    => 'Alamat',
        'edit_your_business_address' => 'Sunting alamat bisnis Anda',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Tetapkan tahun fiskal, zona waktu, format tanggal dan lebih banyak bahasa lokal',
        'financial_start'   => 'Awal Tahun Keuangan',
        'timezone'          => 'Zona Waktu',
        'financial_denote' => [
            'title'         => 'Penunjukkan Tahun Keuangan',
            'begins'        => 'Pada tahun dimulainya',
            'ends'          => 'Pada tahun berakhirnya',
        ],
        'date' => [
            'format'        => 'Format Tanggal',
            'separator'     => 'Pemisah Tanggal',
            'dash'          => 'Strip (-)',
            'dot'           => 'Titik (.)',
            'comma'         => 'Koma (,)',
            'slash'         => 'Garis Miring (/)',
            'space'         => 'Spasi ( )',
        ],
        'percent' => [
            'title'         => 'Posisi (%) Persen',
            'before'        => 'Sebelum Nomor',
            'after'         => 'Sesudah Nomor',
        ],
        'discount_location' => [
            'name'          => 'Lokasi Diskon',
            'item'          => 'Pada baris',
            'total'         => 'Pada jumlah',
            'both'          => 'Pada baris dan jumlah',
        ],
    ],

    'invoice' => [
        'description'       => 'Kustomisasi awalan faktur, nomor, syarat, catatan kaki dll',
        'prefix'            => 'Prefiks Nomor',
        'digit'             => 'Digit Nomor',
        'next'              => 'Nomor Berikutnya',
        'logo'              => 'Logo',
        'custom'            => 'Khusus',
        'item_name'         => 'Nama Item',
        'item'              => 'Item',
        'product'           => 'Produk',
        'service'           => 'Layanan',
        'price_name'        => 'Nama Harga',
        'price'             => 'Harga',
        'rate'              => 'Tarif',
        'quantity_name'     => 'Nama Kuantitas',
        'quantity'          => 'Kuantitas',
        'payment_terms'     => 'Syarat Pembayaran',
        'title'             => 'Judul',
        'subheading'        => 'Subjudul',
        'due_receipt'       => 'Jatuh tempo saat diterima',
        'due_days'          => 'Jatuh tempo dalam :days hari',
        'choose_template'   => 'Pilih template faktur',
        'default'           => 'Default',
        'classic'           => 'Klasik',
        'modern'            => 'Modern',
        'hide'              => [
            'item_name'         => 'Sembunyikan Nama Item',
            'item_description'  => 'Sembunyikan Deskripsi Item',
            'quantity'          => 'Sembunyikan Jumlah',
            'price'             => 'Sembunyikan Harga',
            'amount'            => 'Sembunyikan Jumlah',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'Pilih contoh transfer',
        'second'            => 'Kedua',
        'third'             => 'Ketiga',
    ],

    'default' => [
        'description'       => 'Akun default, mata uang, bahasa perusahaan Anda',
        'list_limit'        => 'Data Per Laman',
        'use_gravatar'      => 'Gunakan Gravatar',
        'income_category'   => 'Kategori Pemasukan',
        'expense_category'  => 'Kategori Pengeluaran',
    ],

    'email' => [
        'description'       => 'Ubah protokol pengiriman dan template email',
        'protocol'          => 'Protokol',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Host SMTP',
            'port'          => 'Port SMTP',
            'username'      => 'Nama Pengguna SMTP',
            'password'      => 'Kata Sandi SMTP',
            'encryption'    => 'Keamanan SMTP',
            'none'          => 'Tidak ada',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => ' Path Sendmail',
        'log'               => 'Log Email',

        'templates' => [
            'subject'                   => 'Subjek',
            'body'                      => 'Badan',
            'tags'                      => '<strong>Tag yang Tersedia:</strong> :tag_list',
            'invoice_new_customer'      => 'Template Faktur Baru (dikirim ke pelanggan)',
            'invoice_remind_customer'   => 'Template Pengingat Faktur (dikirim ke pelanggan)',
            'invoice_remind_admin'      => 'Template Pengingat Faktur (dikirim ke admin)',
            'invoice_recur_customer'    => 'Template Berulang Faktur (dikirim ke pelanggan)',
            'invoice_recur_admin'       => 'Template Berulang Faktur (dikirim ke admin)',
            'invoice_payment_customer'  => 'Template Penerimaan Pembayaran (dikirim ke pelanggan)',
            'invoice_payment_admin'     => 'Template Penerimaan Pembayaran (dikirim ke admin)',
            'bill_remind_admin'         => 'Template Pengingat Tagihan (dikirim ke admin)',
            'bill_recur_admin'          => 'Template Perulangan Tagihan (dikirim ke admin)',
            'revenue_new_customer'      => 'Template Penerimaan Pendapatan (dikirim ke pelanggan)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Penjadwalan',
        'description'       => 'Pengingat dan perintah otomatis untuk perulangan',
        'send_invoice'      => 'Kirim Pengingat Faktur',
        'invoice_days'      => 'Kirim Setelah Hari Jatuh Tempo',
        'send_bill'         => 'Kirim Pengingat Tagihan',
        'bill_days'         => 'Kirim Sebelum Hari Jatuh Tempo',
        'cron_command'      => 'Perintah Cron',
        'schedule_time'     => 'Waktu untuk Menjalankan',
    ],

    'categories' => [
        'description'       => 'Kategori tidak terbatas untuk penghasilan, pengeluaran, dan item',
    ],

    'currencies' => [
        'description'       => 'Buat dan kelola mata uang dan tetapkan nilainya',
    ],

    'taxes' => [
        'description'       => 'Tarif pajak tetap, normal, inklusif, dan gabungan',
    ],

];
