<?php

return [

    'company' => [
        'description'                   => 'Ubah nama perusahaan, email, alamat, NPWP dll',
        'search_keywords'               => 'perusahaan, nama, e-mail, telepon, alamat, negara, nomor pajak, logo, kota, kota, negara bagian, provinsi, kode pos',
        'name'                          => 'Nama',
        'email'                         => 'Email',
        'phone'                         => 'Telepon',
        'address'                       => 'Alamat',
        'edit_your_business_address'    => 'Sunting alamat bisnis Anda',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Informasi ini terlihat dalam rekaman yang Anda buat.',
            'address'                   => 'Alamat tersebut akan digunakan dalam faktur, tagihan, dan catatan lain yang Anda keluarkan.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Tetapkan tahun fiskal, zona waktu, format tanggal dan lebih banyak bahasa lokal',
        'search_keywords'               => 'keuangan, tahun, mulai, menunjukkan, waktu, zona, tanggal, format, pemisah, diskon, persen',
        'financial_start'               => 'Awal Tahun Keuangan',
        'timezone'                      => 'Zona Waktu',
        'financial_denote' => [
            'title'                     => 'Penunjukkan Tahun Keuangan',
            'begins'                    => 'Pada tahun dimulainya',
            'ends'                      => 'Pada tahun berakhirnya',
        ],
        'preferred_date'                => 'Tanggal Pilihan',
        'date' => [
            'format'                    => 'Format Tanggal',
            'separator'                 => 'Pemisah Tanggal',
            'dash'                      => 'Strip (-)',
            'dot'                       => 'Titik (.)',
            'comma'                     => 'Koma (,)',
            'slash'                     => 'Garis Miring (/)',
            'space'                     => 'Spasi ( )',
        ],
        'percent' => [
            'title'                     => 'Posisi (%) Persen',
            'before'                    => 'Sebelum Nomor',
            'after'                     => 'Sesudah Nomor',
        ],
        'discount_location' => [
            'name'                      => 'Lokasi Diskon',
            'item'                      => 'Pada baris',
            'total'                     => 'Pada jumlah',
            'both'                      => 'Pada baris dan jumlah',
        ],

        'form_description' => [
            'fiscal'                    => 'Tetapkan periode tahun keuangan yang digunakan perusahaan Anda untuk perpajakan dan pelaporan.',
            'date'                      => 'Pilih format tanggal yang ingin Anda lihat di mana saja di antarmuka.',
            'other'                     => 'Pilih di mana tanda persentase ditampilkan untuk pajak. Anda dapat mengaktifkan diskon pada item baris dan total untuk faktur dan tagihan.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Kustomisasi awalan faktur, nomor, syarat, catatan kaki dll',
        'search_keywords'               => 'menyesuaikan, faktur, jumlah, awalan, angka, berikutnya, logo, nama, harga, jumlah, Template, judul, subpos, footer, catatan, menyembunyikan, karena, warna, pembayaran, istilah, kolom',
        'prefix'                        => 'Prefiks Nomor',
        'digit'                         => 'Digit Nomor',
        'next'                          => 'Nomor Berikutnya',
        'logo'                          => 'Logo',
        'custom'                        => 'Khusus',
        'item_name'                     => 'Nama Item',
        'item'                          => 'Item',
        'product'                       => 'Produk',
        'service'                       => 'Layanan',
        'price_name'                    => 'Nama Harga',
        'price'                         => 'Harga',
        'rate'                          => 'Tarif',
        'quantity_name'                 => 'Nama Kuantitas',
        'quantity'                      => 'Kuantitas',
        'payment_terms'                 => 'Syarat Pembayaran',
        'title'                         => 'Judul',
        'subheading'                    => 'Subjudul',
        'due_receipt'                   => 'Jatuh tempo saat diterima',
        'due_days'                      => 'Jatuh tempo dalam :days hari',
        'choose_template'               => 'Pilih template faktur',
        'default'                       => 'Default',
        'classic'                       => 'Klasik',
        'modern'                        => 'Modern',
        'hide' => [
            'item_name'                 => 'Sembunyikan Nama Item',
            'item_description'          => 'Sembunyikan Deskripsi Item',
            'quantity'                  => 'Sembunyikan Jumlah',
            'price'                     => 'Sembunyikan Harga',
            'amount'                    => 'Sembunyikan Jumlah',
        ],
        'column'                        => 'Kolom|Kolom',

        'form_description' => [
            'general'                   => 'Tetapkan default untuk memformat nomor faktur dan persyaratan pembayaran Anda.',
            'template'                  => 'Pilih salah satu template di bawah ini untuk faktur Anda.',
            'default'                   => 'Memilih opsi bawaan untuk faktur akan mengisi judul, subjudul, catatan, dan footer terlebih dahulu. Anda tidak perlu mengedit faktur setiap saat agar terlihat lebih profesional.',
            'column'                    => 'Sesuaikan bagaimana kolom faktur diberi nama. Jika Anda ingin menyembunyikan deskripsi item dan jumlah dalam baris, Anda dapat mengubahnya di sini.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Pilih contoh transfer',
        'second'                        => 'Kedua',
        'third'                         => 'Ketiga',
    ],

    'default' => [
        'description'                   => 'Akun default, mata uang, bahasa perusahaan Anda',
        'search_keywords'               => 'akun, mata uang, bahasa, pajak, pembayaran, metode, paginasi',
        'list_limit'                    => 'Data Per Laman',
        'use_gravatar'                  => 'Gunakan Gravatar',
        'income_category'               => 'Kategori Pemasukan',
        'expense_category'              => 'Kategori Pengeluaran',

        'form_description' => [
            'general'                   => 'Pilih akun bawaan, pajak, dan metode pembayaran untuk membuat catatan dengan cepat. Dasbor dan Laporan ditampilkan di bawah mata uang default.',
            'category'                  => 'Pilih kategori bawaan untuk mempercepat pembuatan rekaman.',
            'other'                     => 'Kustomisasi pengaturan bawaan bahasa yang digunakan perusahaan dan cara kerja pemberian nomor pada halaman.',
        ],
    ],

    'email' => [
        'description'                   => 'Ubah protokol pengiriman dan template email',
        'search_keywords'               => 'email, kirim, protokol, smtp, host, kata sandi',
        'protocol'                      => 'Protokol',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Host SMTP',
            'port'                      => 'Port SMTP',
            'username'                  => 'Nama Pengguna SMTP',
            'password'                  => 'Kata Sandi SMTP',
            'encryption'                => 'Keamanan SMTP',
            'none'                      => 'Tidak ada',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => ' Path Sendmail',
        'log'                           => 'Log Email',
        'email_service'                 => 'Layanan Email',
        'email_templates'               => 'Template Email',

        'form_description' => [
            'general'                   => 'Kirim email reguler ke tim dan kontak Anda. Anda dapat mengatur protokol dan pengaturan SMTP.',
        ],

        'templates' => [
            'description'               => 'Ubah template email',
            'search_keywords'           => 'email, template, judul, isi, tag',
            'subject'                   => 'Subjek',
            'body'                      => 'Badan',
            'tags'                      => '<strong>Tag yang Tersedia:</strong> :tag_list',
            'invoice_new_customer'      => 'Template Faktur Baru (dikirim ke pelanggan)',
            'invoice_remind_customer'   => 'Template Pengingat Faktur (dikirim ke pelanggan)',
            'invoice_remind_admin'      => 'Template Pengingat Faktur (dikirim ke admin)',
            'invoice_recur_customer'    => 'Template Berulang Faktur (dikirim ke pelanggan)',
            'invoice_recur_admin'       => 'Template Berulang Faktur (dikirim ke admin)',
            'invoice_view_admin'        => 'Template Tampilan Faktur (dikirim ke admin)',
            'invoice_payment_customer'  => 'Template Penerimaan Pembayaran (dikirim ke pelanggan)',
            'invoice_payment_admin'     => 'Template Penerimaan Pembayaran (dikirim ke admin)',
            'bill_remind_admin'         => 'Template Pengingat Tagihan (dikirim ke admin)',
            'bill_recur_admin'          => 'Template Perulangan Tagihan (dikirim ke admin)',
            'payment_received_customer' => 'Template Tanda Terima Pembayaran (dikirim ke pelanggan)',
            'payment_made_vendor'       => 'Template Pembayaran (dikirim ke vendor)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Penjadwalan',
        'description'                   => 'Pengingat dan perintah otomatis untuk perulangan',
        'search_keywords'               => 'otomatis, pengingat, pengulangan, cron, perintah',
        'send_invoice'                  => 'Kirim Pengingat Faktur',
        'invoice_days'                  => 'Kirim Setelah Hari Jatuh Tempo',
        'send_bill'                     => 'Kirim Pengingat Tagihan',
        'bill_days'                     => 'Kirim Sebelum Hari Jatuh Tempo',
        'cron_command'                  => 'Perintah Cron',
        'command'                       => 'Perintah',
        'schedule_time'                 => 'Waktu untuk Menjalankan',

        'form_description' => [
            'invoice'                   => 'Aktifkan atau nonaktifkan, dan atur pengingat untuk faktur Anda saat jatuh tempo.',
            'bill'                      => 'Aktifkan atau nonaktifkan, dan atur pengingat untuk tagihan Anda sebelum jatuh tempo.',
            'cron'                      => 'Salin perintah cron yang harus dijalankan server Anda. Atur waktu untuk memicu suatu hal.',
        ]
    ],

    'categories' => [
        'description'                   => 'Kategori tidak terbatas untuk penghasilan, pengeluaran, dan item',
        'search_keywords'               => 'kategori, pemasukan, pengeluaran, barang',
    ],

    'currencies' => [
        'description'                   => 'Buat dan kelola mata uang dan tetapkan nilainya',
        'search_keywords'               => 'default, mata uang, mata-mata uang, kode, kurs, simbol, presisi, posisi, desimal, ribuan, tanda, pemisah
',
    ],

    'taxes' => [
        'description'                   => 'Tarif pajak tetap, normal, inklusif, dan gabungan',
        'search_keywords'               => 'pajak, tarif, jenis, tetap, inklusif, majemuk, pemotongan',
    ],

];
