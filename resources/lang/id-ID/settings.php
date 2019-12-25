<?php

return [

    'company' => [
        'description'       => 'Ubah nama perusahaan, email, alamat, NPWP dll',
        'name'              => 'Nama',
        'email'             => 'Email',
        'phone'             => 'Telpon',
        'address'           => 'Alamat',
        'logo'              => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Set fiscal year, time zone, date format and more locals',
        'financial_start'   => 'Waktu mulai Periode Keuangan',
        'timezone'          => 'Zona Waktu',
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
    ],

    'invoice' => [
        'description'       => 'Customize invoice prefix, number, terms, footer etc',
        'prefix'            => 'Prefix Nomor',
        'digit'             => 'Digit nomor',
        'next'              => 'Nomor Berikutnya',
        'logo'              => 'Logo',
        'custom'            => 'Personalisasi',
        'item_name'         => 'Nama Barang',
        'item'              => 'Barang',
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
    ],

    'default' => [
        'description'       => 'Default account, currency, language of your company',
        'list_limit'        => 'Records Per Page',
        'use_gravatar'      => 'Use Gravatar',
    ],

    'email' => [
        'description'       => 'Change the sending protocol and email templates',
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
            'subject'                   => 'Subject',
            'body'                      => 'Body',
            'tags'                      => '<strong>Available Tags:</strong> :tag_list',
            'invoice_new_customer'      => 'New Invoice Template (sent to customer)',
            'invoice_remind_customer'   => 'Invoice Reminder Template (sent to customer)',
            'invoice_remind_admin'      => 'Invoice Reminder Template (sent to admin)',
            'invoice_recur_customer'    => 'Invoice Recurring Template (sent to customer)',
            'invoice_recur_admin'       => 'Invoice Recurring Template (sent to admin)',
            'invoice_payment_customer'  => 'Payment Received Template (sent to customer)',
            'invoice_payment_admin'     => 'Payment Received Template (sent to admin)',
            'bill_remind_admin'         => 'Bill Reminder Template (sent to admin)',
            'bill_recur_admin'          => 'Bill Recurring Template (sent to admin)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Scheduling',
        'description'       => 'Automatic reminders and command for recurring',
        'send_invoice'      => 'Kirim Pengingat Faktur',
        'invoice_days'      => 'Kirim Setelah Jatuh Tempo',
        'send_bill'         => 'Kirim Pengingat Tagihan',
        'bill_days'         => 'Kirim Sebelum Jatuh Tempo',
        'cron_command'      => 'Perintah Cron',
        'schedule_time'     => 'Waktu untuk Menjalankan',
    ],

    'categories' => [
        'description'       => 'Unlimited categories for income, expense, and item',
    ],

    'currencies' => [
        'description'       => 'Create and manage currencies and set their rates',
    ],

    'taxes' => [
        'description'       => 'Fixed, normal, inclusive, and compound tax rates',
    ],

];
