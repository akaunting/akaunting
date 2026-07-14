<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Halo!',
    'salutation'          => 'Hormat Kami,<br> :company_name',
    'subcopy'             => 'Jika terdapat masalah ketika menekan tombol ":text", salin dan tempel URL tersebut pada peramban web Anda: [:url](:url)',
    'mark_read'           => 'Tandai Dibaca',
    'mark_read_all'       => 'Tandai Dibaca Semua',
    'empty'               => 'Hore, tidak ada notifikasi!',
    'new_apps'            => ':app tersedia. <a href=":url">Lihat sekarang</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Update gagal di :domain',
            'description'   => 'Pembaruan :alias dari :current_version ke :new_version gagal dalam <strong>:step</strong> langkah dengan pesan berikut: :error_message',

        ],

        'slack' => [

            'description'   => 'Update gagal di :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Unduhan siap',
            'description'   => 'Berkas siap diunduh dari tautan berikut:',

        ],

        'failed' => [

            'title'         => 'Unduhan gagal',
            'description'   => 'Tidak dapat membuat berkas dikarenakan masalah berikut:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Impor selesai',
            'description'   => 'Impor telah selesai dan rekaman data telah tersedia di panel Anda.',

        ],

        'failed' => [

            'title'         => 'Impor gagal',
            'description'   => 'Tidak dapat mengimpor berkas dikarenakan beberapa masalah. Periksa email Anda untuk detailnya.',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Ekspor siap',
            'description'   => 'Berkas ekspor siap untuk diunduh dari tautan berikut:',

        ],

        'failed' => [

            'title'         => 'Ekspor gagal',
            'description'   => 'Tidak dapat membuat berkas ekspor dikarenakan beberapa masalah. Periksa email Anda untuk detailnya.',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'Email :type tidak valid',
            'description'   => 'Alamat email :email telah dilaporkan tidak valid, dan orang tersebut telah dinonaktifkan. Silakan periksa pesan galat berikut dan perbaiki alamat email:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Unduhan siap',
            'description'   => 'Berkas <strong>:type</strong> Anda siap untuk <a href=":url" target="_blank"><strong>diunduh</strong></a>.',
        ],

        'download_failed' => [

            'title'         => 'Unduhan gagal',
            'description'   => 'Tidak dapat membuat berkas dikarenakan beberapa masalah. Periksa email Anda untuk detailnya.',

        ],

        'export_completed' => [

            'title'         => 'Ekspor siap',
            'description'   => 'Berkas ekspor <strong>:type</strong> Anda siap untuk <a href=":url" target="_blank"><strong>diunduh</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Ekspor gagal',
            'description'   => 'Tidak dapat membuat berkas ekspor dikarenakan beberapa masalah. Periksa email Anda untuk detailnya.',

        ],

        'import_completed' => [

            'title'         => 'Impor telah selesai',
            'description'   => '<strong>:type</strong> baris <strong>:count</strong> data Anda berhasil diimpor.',

        ],

        'import_failed' => [

            'title'         => 'Impor gagal',
            'description'   => 'Tidak dapat mengimpor berkas dikarenakan beberapa masalah. Periksa email Anda untuk detailnya.',

        ],

        'new_apps' => [

            'title'         => 'Aplikasi Baru',
            'description'   => '<strong>:name</strong> aplikasi telah rilis. Anda dapat <a href=":url">klik di sini</a> untuk melihat detailnya.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Faktur Baru',
            'description'   => 'Faktur <strong>:invoice_number</strong> dibuat. Anda dapat <a href=":invoice_portal_link">klik di sini</a> untuk melihat detailnya dan melanjutkan pembayaran.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Faktur Terlambat',
            'description'   => '<strong>:invoice_number</strong> faktur jatuh tempo <strong>:invoice_due_date</strong>. Anda dapat <a href=":invoice_portal_link">klik di sini</a> untuk melihat detailnya dan melanjutkan pembayaran.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Faktur Terlambat',
            'description'   => '<strong>:invoice_number</strong> faktur jatuh tempo <strong>:invoice_due_date</strong>. Anda dapat <a href=":invoice_admin_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Faktur Berulang Baru',
            'description'   => 'Faktur <strong>:invoice_number</strong> dibuat berdasarkan siklus berulang Anda. Anda dapat <a href=":invoice_portal_link">klik di sini</a> untuk melihat detailnya dan melanjutkan pembayaran.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Faktur Berulang Baru',
            'description'   => 'Faktur <strong>:invoice_number</strong> dibuat berdasarkan <strong>:customer_name</strong> siklus berulang. Anda dapat <a href=":invoice_admin_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Faktur Dilihat',
            'description'   => '<strong>:customer_name</strong> telah melihat faktur <strong>:invoice_number</strong>. Anda dapat <a href=":invoice_admin_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Pembayaran Diterima',
            'description'   => 'Terima kasih atas pembayaran untuk faktur <strong>:invoice_number</strong>. Anda dapat <a href=":invoice_portal_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Pembayaran Diterima',
            'description'   => 'Terima kasih atas pembayaran untuk faktur <strong>:invoice_number</strong>. Anda dapat <a href=":invoice_portal_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Pembayaran Diterima',
            'description'   => ':customer_name mencatat pembayaran untuk faktur <strong>:invoice_number</strong>. Anda dapat <a href=":invoice_admin_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Tagihan Terlambat',
            'description'   => 'Tagihan <strong>:bill_number</strong> telah jatuh tempo <strong>:bill_due_date</strong>. Anda dapat <a href=":bill_admin_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Tagihan Berulang Baru',
            'description'   => 'Tagihan <strong>:bill_number</strong> dibuat berdasarkan <strong>:vendor_name</strong> siklus berulang. Anda dapat <a href=":bill_admin_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'invalid_email' => [

            'title'         => 'Email :type tidak valid',
            'description'   => 'Alamat email <strong>:email</strong> telah dilaporkan tidak valid, dan orang tersebut telah dinonaktifkan. Silakan periksa dan perbaiki alamat email.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type membaca notifikasi ini!',
        'mark_read_all'         => ':type telah membaca semua notifikasi!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Konfigurasi Ikon Firefox',
            'description'  => '<span class="font-medium">Jika ikon Anda tidak muncul, harap;</span> <br /> <span class="font-medium">Harap Izinkan halaman untuk memilih font mereka sendiri, bukan pilihan Anda di atas</span> <br /><br /> <span class="font-bold"> Pengaturan (Preferensi) > Font > Lanjutan </span>',

        ],

    ],

];
