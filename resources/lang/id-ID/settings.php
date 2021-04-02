<?php

return [

    'company' => [
        'description'                => 'Ubah nama perusahaan, email, alamat, NPWP dll',
        'name'                       => 'Nama',
        'email'                      => 'Email',
        'phone'                      => 'Telpon',
        'address'                    => 'Alamat',
        'edit_your_business_address' => 'Ubah alamat bisnis Anda',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Tetapkan tahun fiskal, zona waktu, format tanggal dan lebih banyak penduduk lokal',
        'financial_start'   => 'Waktu mulai Periode Keuangan',
        'timezone'          => 'Zona Waktu',
        'financial_denote' => [
            'title'         => 'Tahun Keuangan Menunjukkan',
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
            'title'         => 'Persen (%) Posisi',
            'before'        => 'Sebelum Nomor',
            'after'         => 'Sesudah Nomor',
        ],
        'discount_location' => [
            'name'          => 'Lokasi Diskon',
            'item'          => 'pada baris',
            'total'         => 'Pada jumlah',
            'both'          => 'Pada baris dan jumlah',
        ],
    ],

    'invoice' => [
        'description'       => 'Kustomisasi awalan faktur, nomor, syarat, catatan kaki dll',
        'prefix'            => 'Prefix Nomor',
        'digit'             => 'Digit nomor',
        'next'              => 'Nomor Berikutnya',
        'logo'              => 'Logo',
        'custom'            => 'Personalisasi',
        'item_name'         => 'Nama Barang',
        'item'              => 'Isi',
        'product'           => 'Produk',
        'service'           => 'Layanan',
        'price_name'        => 'Nama Harga',
        'price'             => 'Harga',
        'rate'              => 'Kurs',
        'quantity_name'     => 'Nama Kuantitas',
        'quantity'          => 'Kuantitas',
        'payment_terms'     => 'Syarat pembayaran',
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

    'default' => [
        'description'       => 'Akun default, mata uang, bahasa perusahaan Anda',
        'list_limit'        => 'Data Per Laman',
        'use_gravatar'      => 'Gunakan Gravatar',
        'income_category'   => 'Kategori Pemasukan',
        'expense_category'  => 'Kategori Biaya',
    ],

    'email' => [
        'description'       => 'Ubah protokol pengiriman dan templat email',
        'protocol'          => 'Protokol',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Port',
            'username'      => 'Nama Pengguna SMTP',
            'password'      => 'Kata Sandi SMTP',
            'encryption'    => 'Keamanan SMTP',
            'none'          => 'Tidak ada',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Path',
        'log'               => 'Log Email',

        'templates' => [
            'subject'                   => 'Subjek',
            'body'                      => 'Badan',
            'tags'                      => '<strong> Tag yang Tersedia: </strong>: tag_list',
            'invoice_new_customer'      => 'Templat Faktur Baru (dikirim ke pelanggan)',
            'invoice_remind_customer'   => 'Templat Pengingat Faktur (dikirim ke pelanggan)',
            'invoice_remind_admin'      => 'Templat Pengingat Faktur (dikirim ke admin)',
            'invoice_recur_customer'    => 'Templat Berulang Faktur (dikirim ke pelanggan)',
            'invoice_recur_admin'       => 'Templat Berulang Faktur (dikirim ke admin)',
            'invoice_payment_customer'  => 'Template Penerimaan Pembayaran (dikirim ke pelanggan)',
            'invoice_payment_admin'     => 'Template Penerimaan Pembayaran (dikirim ke admin)',
            'bill_remind_admin'         => 'Templat Pengingat Tagihan (dikirim ke admin)',
            'bill_recur_admin'          => 'Templat Perulangan Tagihan (dikirim ke admin)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Penjadwalan',
        'description'       => 'Pengingat dan perintah otomatis untuk berulang',
        'send_invoice'      => 'Kirim Pengingat Faktur',
        'invoice_days'      => 'Kirim Setelah Jatuh Tempo',
        'send_bill'         => 'Kirim Pengingat Tagihan',
        'bill_days'         => 'Kirim Sebelum Jatuh Tempo',
        'cron_command'      => 'Perintah Cron',
        'schedule_time'     => 'Waktu untuk Menjalankan',
    ],

    'categories' => [
        'description'       => 'Kategori tidak terbatas untuk penghasilan, pengeluaran, dan barang',
    ],

    'currencies' => [
        'description'       => 'Buat dan kelola mata uang dan tetapkan nilainya',
    ],

    'taxes' => [
        'description'       => 'Tarif pajak tetap, normal, inklusif, dan gabungan',
    ],

];
