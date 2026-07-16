<?php

return [

    'company' => [
        'description'                   => 'Tukar nama syarikat, e-mel, alamat, nombor cukai, dll',
        'search_keywords'               => 'syarikat, nama,e-mel, telefon, alamat, negara, nombor cukai, logo, bandar, negeri, wilayah, poskod',
        'name'                          => 'Nama',
        'email'                         => 'E-mel',
        'phone'                         => 'Telefon',
        'address'                       => 'Alamat',
        'edit_your_business_address'    => 'Sunting alamat perniagaan anda',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Maklumat ini terpapar dalam rekod yang anda cipta.',
            'address'                   => 'Alamat akan digunakan dalam invois, bil, dan rekod lain yang anda keluarkan.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Tetapkan tahun fiskal, zon waktu, format tarikh dan penyetempatan lain',
        'search_keywords'               => 'kewangan, tahun, mula, tunjuk, masa, zon, tarikh, format, pemisah, diskaun, peratus',
        'financial_start'               => 'Permulaan Tahun Kewangan',
        'timezone'                      => 'Zon Waktu',
        'financial_denote' => [
            'title'                     => 'Penandaan Tahun Kewangan',
            'begins'                    => 'Mengikut tahun ia bermula',
            'ends'                      => 'Mengikut tahun ia berakhir',
        ],
        'preferred_date'                => 'Tarikh Pilihan',
        'date' => [
            'format'                    => 'Format Tarikh',
            'separator'                 => 'Pemisah Tarikh',
            'dash'                      => 'Sempang (-)',
            'dot'                       => 'Titik (.)',
            'comma'                     => 'Koma (,)',
            'slash'                     => 'Garis Miring (/)',
            'space'                     => 'Ruang ( )',
        ],
        'percent' => [
            'title'                     => 'Kedudukan Tanda Peratus (%)',
            'before'                    => 'Sebelum Nombor',
            'after'                     => 'Selepas Nombor',
        ],
        'discount_location' => [
            'name'                      => 'Lokasi Diskaun',
            'item'                      => 'Pada baris',
            'total'                     => 'Pada jumlah keseluruhan',
            'both'                      => 'Pada baris dan jumlah keseluruhan',
        ],

        'form_description' => [
            'fiscal'                    => 'Tetapkan tempoh tahun kewangan yang digunakan oleh syarikat anda untuk percukaian dan pelaporan.',
            'date'                      => 'Pilih format tarikh yang anda ingin lihat di mana-mana dalam antara muka.',
            'other'                     => 'Pilih di mana tanda peratus dipaparkan untuk cukai. Anda boleh mengaktifkan diskaun pada baris item dan pada jumlah keseluruhan untuk invois dan bil.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Sesuaikan awalan invois, nombor, terma, pengaki, dll',
        'search_keywords'               => 'sesuaikan, invois, nombor, awalan, digit, seterusnya, logo, nama, harga, kuantiti, templat, tajuk, subtajuk, pengaki, nota, sembunyi, jatuh tempo, warna, bayaran, terma, lajur',
        'prefix'                        => 'Awalan Nombor',
        'digit'                         => 'Digit Nombor',
        'next'                          => 'Nombor Seterusnya',
        'logo'                          => 'Logo',
        'custom'                        => 'Tersuai',
        'item_name'                     => 'Nama Item',
        'item'                          => 'Item',
        'product'                       => 'Produk',
        'service'                       => 'Perkhidmatan',
        'price_name'                    => 'Nama Harga',
        'price'                         => 'Harga',
        'rate'                          => 'Kadar',
        'quantity_name'                 => 'Nama Kuantiti',
        'quantity'                      => 'Kuantiti',
        'payment_terms'                 => 'Terma Bayaran',
        'title'                         => 'Tajuk',
        'subheading'                    => 'Subtajuk',
        'due_receipt'                   => 'Tertunggak upon resit',
        'due_days'                      => 'Tertunggak dalam :days hari',
        'due_custom'                    => 'Hari tersuai',
        'due_custom_day'                => 'selepas hari',
        'choose_template'               => 'Pilih templat invois',
        'default'                       => 'Lalai',
        'classic'                       => 'Klasik',
        'modern'                        => 'Moden',
        'logo_size_width'               => 'Lebar Logo',
        'logo_size_height'              => 'Tinggi Logo',
        'hide' => [
            'item_name'                 => 'Sembunyikan Nama Item',
            'item_description'          => 'Sembunyikan Penerangan Item',
            'quantity'                  => 'Sembunyikan Kuantiti',
            'price'                     => 'Sembunyikan Harga',
            'amount'                    => 'Sembunyikan Jumlah',
        ],
        'column'                        => 'Lajur|Lajur',

        'form_description' => [
            'general'                   => 'Tetapkan lalai untuk memformat nombor invois dan terma bayaran anda.',
            'template'                  => 'Pilih salah satu templat di bawah untuk invois anda.',
            'default'                   => 'Memilih lalai untuk invois akan mengisi tajuk, subtajuk, nota, dan pengaki terlebih dahulu. Jadi anda tidak perlu menyunting invois setiap kali untuk kelihatan lebih profesional.',
            'column'                    => 'Sesuaikan bagaimana lajur invois dinamakan. Jika anda ingin menyembunyikan penerangan item dan jumlah dalam baris, anda boleh mengubahnya di sini.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Pilih templat pemindahan',
        'second'                        => 'Kedua',
        'third'                         => 'Ketiga',
    ],

    'default' => [
        'description'                   => 'Akaun lalai, mata wang, bahasa syarikat anda',
        'search_keywords'               => 'akaun, mata wang, bahasa, cukai, bayaran, kaedah, penomboran halaman',
        'list_limit'                    => 'Rekod Setiap Halaman',
        'use_gravatar'                  => 'Gunakan Gravatar',
        'income_category'               => 'Kategori Pendapatan',
        'expense_category'              => 'Kategori Perbelanjaan',
        'address_format'                => 'Format Alamat',
        'address_tags'                  => '<strong>Tag Tersedia:</strong> :tags',

        'form_description' => [
            'general'                   => 'Pilih akaun lalai, cukai, dan kaedah bayaran untuk mencipta rekod dengan pantas. Papan Pemuka dan Laporan dipaparkan di bawah mata wang lalai.',
            'category'                  => 'Pilih kategori lalai untuk mempercepat penciptaan rekod.',
            'other'                     => 'Sesuaikan tetapan lalai bahasa syarikat dan cara penomboran halaman berfungsi.',
        ],
    ],

    'email' => [
        'description'                   => 'Tukar protokol penghantaran',
        'search_keywords'               => 'e-mel, hantar, protokol, smtp, hos, kata laluan',
        'protocol'                      => 'Protokol',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Hos SMTP',
            'port'                      => 'Port SMTP',
            'username'                  => 'Nama Pengguna SMTP',
            'password'                  => 'Kata Laluan SMTP',
            'encryption'                => 'Keselamatan SMTP',
            'none'                      => 'Tiada',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Laluan Sendmail',
        'log'                           => 'Log E-mel',
        'email_service'                 => 'Perkhidmatan E-mel',
        'email_templates'               => 'Templat E-mel',

        'form_description' => [
            'general'                   => 'Hantar e-mel biasa kepada pasukan dan kontak anda. Anda boleh menetapkan protokol dan tetapan SMTP.',
        ],

        'templates' => [
            'description'               => 'Tukar templat e-mel',
            'search_keywords'           => 'e-mel, templat, subjek, kandungan, tag',
            'subject'                   => 'Subjek',
            'body'                      => 'Kandungan',
            'tags'                      => '<strong>Tag Tersedia:</strong> :tag_list',
            'invoice_new_customer'      => 'Templat Invois Baharu (dihantar kepada pelanggan)',
            'invoice_remind_customer'   => 'Templat Peringatan Invois (dihantar kepada pelanggan)',
            'invoice_remind_admin'      => 'Templat Peringatan Invois (dihantar kepada pentadbir)',
            'invoice_recur_customer'    => 'Templat Invois Berulang (dihantar kepada pelanggan)',
            'invoice_recur_admin'       => 'Templat Invois Berulang (dihantar kepada pentadbir)',
            'invoice_view_admin'        => 'Templat Paparan Invois (dihantar kepada pentadbir)',
            'invoice_payment_customer'  => 'Templat Resit Bayaran Invois (dihantar kepada pelanggan)',
            'invoice_payment_admin'     => 'Templat Penerimaan Bayaran Invois (dihantar kepada pentadbir)',
            'bill_remind_admin'         => 'Templat Peringatan Bil (dihantar kepada pentadbir)',
            'bill_recur_admin'          => 'Templat Bil Berulang (dihantar kepada pentadbir)',
            'payment_received_customer' => 'Templat Resit Bayaran (dihantar kepada pelanggan)',
            'payment_made_vendor'       => 'Templat Bayaran Dibuat (dihantar kepada pembekal)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Penjadualan',
        'description'                   => 'Peringatan automatik dan arahan untuk berulang',
        'search_keywords'               => 'automatik, peringatan, berulang, cron, arahan',
        'send_invoice'                  => 'Hantar Peringatan Invois',
        'invoice_days'                  => 'Hantar Selepas Hari Tertunggak',
        'send_bill'                     => 'Hantar Peringatan Bil',
        'bill_days'                     => 'Hantar Sebelum Hari Jatuh Tempo',
        'cron_command'                  => 'Arahan Cron',
        'command'                       => 'Arahan',
        'schedule_time'                 => 'Jam Untuk Dijalankan',

        'form_description' => [
            'invoice'                   => 'Aktifkan atau nyahaktifkan, dan tetapkan peringatan untuk invois anda apabila ia tertunggak.',
            'bill'                      => 'Aktifkan atau nyahaktifkan, dan tetapkan peringatan untuk bil anda sebelum ia tertunggak.',
            'cron'                      => 'Salin arahan cron yang pelayan anda patut jalankan. Tetapkan masa untuk mencetuskan peristiwa tersebut.',
        ]
    ],

    'categories' => [
        'description'                   => 'Kategori tanpa had untuk pendapatan, perbelanjaan, dan item',
        'search_keywords'               => 'kategori, pendapatan, perbelanjaan, item',
    ],

    'currencies' => [
        'description'                   => 'Cipta dan urus mata wang dan tetapkan kadarnya',
        'search_keywords'               => 'lalai, mata wang, kod, kadar, simbol, ketepatan, kedudukan, perpuluhan, ribuan, tanda, pemisah',
    ],

    'taxes' => [
        'description'                   => 'Kadar cukai tetap, normal, inklusif, dan kompaun',
        'search_keywords'               => 'cukai, kadar, jenis, tetap, inklusif, kompaun, potongan',
    ],

];
