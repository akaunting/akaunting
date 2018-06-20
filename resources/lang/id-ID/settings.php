<?php

return [

    'company' => [
        'name'              => 'Nama',
        'email'             => 'Email',
        'phone'             => 'Telpon',
        'address'           => 'Alamat',
        'logo'              => 'Logo',
    ],
    'localisation' => [
        'tab'               => 'Lokalisasi',
        'date' => [
            'format'        => 'Format Tanggal',
            'separator'     => 'Pemisah Tanggal',
            'dash'          => 'Strip (-)',
            'dot'           => 'Titik (.)',
            'comma'         => 'Koma (,)',
            'slash'         => 'Garis Miring (/)',
            'space'         => 'Spasi ( )',
        ],
        'timezone'          => 'Zona Waktu',
        'percent' => [
            'title'         => 'Percent (%) Position',
            'before'        => 'Before Number',
            'after'         => 'After Number',
        ],
    ],
    'invoice' => [
        'tab'               => 'Faktur',
        'prefix'            => 'Prefix Nomor',
        'digit'             => 'Digit nomor',
        'next'              => 'Nomor Berikutnya',
        'logo'              => 'Logo',
    ],
    'default' => [
        'tab'               => 'Standar',
        'account'           => 'Akun Utama',
        'currency'          => 'Mata Uang Utama',
        'tax'               => 'Kurs Pajak Utama',
        'payment'           => 'Metode Pembayaran Utama',
        'language'          => 'Bahasa Utama',
    ],
    'email' => [
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
    ],
    'scheduling' => [
        'tab'               => 'Penjadwalan',
        'send_invoice'      => 'Kirim Pengingat Faktur',
        'invoice_days'      => 'Kirim Setelah Jatuh Tempo',
        'send_bill'         => 'Kirim Pengingat Tagihan',
        'bill_days'         => 'Kirim Sebelum Jatuh Tempo',
        'cron_command'      => 'Perintah Cron',
        'schedule_time'     => 'Waktu untuk Menjalankan',
    ],
    'appearance' => [
        'tab'               => 'Tampilan',
        'theme'             => 'Tema',
        'light'             => 'Terang',
        'dark'              => 'Gelap',
        'list_limit'        => 'Data Per Laman',
        'use_gravatar'      => 'Gunakan Gravatar',
    ],
    'system' => [
        'tab'               => 'Sistem',
        'session' => [
            'lifetime'      => 'Batas Waktu Session (Menit)',
            'handler'       => 'Pemegang Session',
            'file'          => 'File',
            'database'      => 'Database',
        ],
        'file_size'         => 'Ukuran Maksimal File (MB)',
        'file_types'        => 'Jenis File Yang Diperbolehkan',
    ],

];
