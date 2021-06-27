<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Halo!',
    'salutation'          => 'Hormat Kami,<br> :company_name',
    'subcopy'             => 'Jika terdapat masalah ketika menekan tombol ":text", copy dan paste URL tersebut pada web browser Anda: [:url](:url)',
    'reads'               => 'Baca|Baca',
    'read_all'            => 'Baca Semua',
    'mark_read'           => 'Tandai Dibaca',
    'mark_read_all'       => 'Tandai Dibaca Semua',
    'new_apps'            => 'Aplikasi Baru|Aplikasi Baru',
    'upcoming_bills'      => 'Tagihan Mendatang',
    'recurring_invoices'  => 'Faktur Berulang',
    'recurring_bills'     => 'Tagihan Berulang',

    'update' => [

        'mail' => [

            'subject' => '⚠️ Pembaruan gagal di :domain',
            'message' => 'Pembaruan dari :alias dari :current_version ke :new_version gagal di langkah <strong>:step</strong> dengan pesan berikut: :error_message',

        ],

        'slack' => [

            'message' => 'Pembaruan gagal di :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'subject'           => 'Impor selesai',
            'description'       => 'Impor telah selesai dan rekaman data telah tersedia di panel Anda.',

        ],

        'failed' => [

            'subject'           => 'Impor gagal',
            'description'       => 'Tidak dapat mengimpor berkas dikarenakan masalah berikut:',

        ],
    ],

    'export' => [

        'completed' => [

            'subject'           => 'Ekspor siap',
            'description'       => 'Berkas ekspor siap untuk diunduh dari tautan berikut:',

        ],

        'failed' => [

            'subject'           => 'Ekspor gagal',
            'description'       => 'Tidak dapat membuat berkas ekspor dikarenakan masalah berikut:',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type membaca notifikasi ini!',
        'mark_read_all'         => ':type telah membaca semua notifikasi!',
        'new_app'               => ':type aplikasi terpublikasi.',
        'export'                => 'Berkas ekspor <b>:type</b> Anda telah siap untuk di <a href=":url" target="_blank"><b>unduh</b></a>.',
        'import'                => 'Data <b>:type</b> di baris <b>:count</b> Anda telah berhasil di impor.',

    ],
];
