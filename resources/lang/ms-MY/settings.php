<?php

return [

    'company' => [
        'description'       => 'Ubah nama syarikat, emel, alamat, nombor tax, dll',
        'name'              => 'Nama',
        'email'             => 'Emel',
        'phone'             => 'Telefon',
        'address'           => 'Alamat',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Tetapkan tahun fiskal, zon masa, format tarikh dan lebih ramai penduduk tempatan',
        'financial_start'   => 'Tahun Kewangan Bermula',
        'timezone'          => 'Zon Masa',
        'date' => [
            'format'        => 'Format Tarikh',
            'separator'     => 'Pemisah Tarikh',
            'dash'          => 'Sempang (-)',
            'dot'           => 'Titik (.)',
            'comma'         => 'Koma (,)',
            'slash'         => 'Palang (/)',
            'space'         => 'Jarak ( )',
        ],
        'percent' => [
            'title'         => 'Kedudukan Peratus (%)',
            'before'        => 'Sebelum Nombor',
            'after'         => 'Selepas Nombor',
        ],
    ],

    'invoice' => [
        'description'       => 'Ubah suai awalan invois, nombor, terma, footer dll',
        'prefix'            => 'Awalan Nombor',
        'digit'             => 'Nombor Digit',
        'next'              => 'Nombor Seterusnya',
        'logo'              => 'Logo',
        'custom'            => 'Kesesuaian Sendiri',
        'item_name'         => 'Nama Item',
        'item'              => 'Item-item',
        'product'           => 'Produk-produk',
        'service'           => 'Perkhidmatan-perkhidmatan',
        'price_name'        => 'Nama Harga',
        'price'             => 'Harga',
        'rate'              => 'Kadar',
        'quantity_name'     => 'Nama Kuantiti',
        'quantity'          => 'Kuantiti',
        'payment_terms'     => 'Terma pembayaran',
        'title'             => 'Tajuk',
        'subheading'        => 'Tajuk kecil',
        'due_receipt'       => 'Tamat saat diterima',
        'due_days'          => 'Tamat dalam tempoh :hari hari',
        'choose_template'   => 'Pilih templat invois',
        'default'           => 'Tetapan asal seperti sediakala',
        'classic'           => 'Klasik',
        'modern'            => 'Moden',
    ],

    'default' => [
        'description'       => 'Tetapan sediakala akaun, matawang, bahasa syarikat anda',
        'list_limit'        => 'Rekod setiap mukasurat',
        'use_gravatar'      => 'Guna Gravatar',
    ],

    'email' => [
        'description'       => 'Tukar protocol penghantaran dan templet emel',
        'protocol'          => 'Protokol',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Port',
            'username'      => 'Nama Pengguna SMTP',
            'password'      => 'Kata Laluan SMTP',
            'encryption'    => 'Sekuriti SMTP',
            'none'          => 'Tiada',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Path',
        'log'               => 'Log Emel',

        'templates' => [
            'subject'                   => 'Subjek',
            'body'                      => 'Badan',
            'tags'                      => '<strong>Tersedia Tags:</strong> :senarai_tag',
            'invoice_new_customer'      => 'Templat invois baru (dihantar kepada pelanggan)',
            'invoice_remind_customer'   => 'Templat peringatan invois (dihantar kepada pelanggan)',
            'invoice_remind_admin'      => 'Templat peringatan invois (dihantar kepada pengurusan)',
            'invoice_recur_customer'    => 'Templat invois berulang (dihantar kepada pelanggan)',
            'invoice_recur_admin'       => 'Templat invois berulang (dihantar kepada pengurusan)',
            'invoice_payment_customer'  => 'Templat pembayaran diterima (dihantar kepada pelanggan)',
            'invoice_payment_admin'     => 'Templat pembayaran diterima (dihantar kepada pengurusan)',
            'bill_remind_admin'         => 'Peringatan bil berulang (dihantar kepada pengurusan)',
            'bill_recur_admin'          => 'Templat bil berulang (dihantar kepada pengurusan)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Penjadualan',
        'description'       => 'Peringatan automatik dan perintah untuk ulangan',
        'send_invoice'      => 'Hantar Peringatan Invois',
        'invoice_days'      => 'Hantar Selepas Hari Matang',
        'send_bill'         => 'Hantar Peringatan Bil',
        'bill_days'         => 'Hantar Sebelum Hari Matang',
        'cron_command'      => 'Cron Command',
        'schedule_time'     => 'Jam untuk laksanakan',
    ],

    'categories' => [
        'description'       => 'Kategori tanpa had untuk pendapatan, perbelanjaan, dan item',
    ],

    'currencies' => [
        'description'       => 'Cipta dan urus matawang dan tetapkan kadar mereka',
    ],

    'taxes' => [
        'description'       => 'Tetap, biasa, termasuk, dan kadar cukai kompaun',
    ],

];
