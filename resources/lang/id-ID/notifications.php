<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Halo!',
    'salutation'          => 'Hormat Kami,<br> :company_name',
    'subcopy'             => 'Jika terdapat masalah ketika menekan tombol ":text", copy dan paste URL tersebut pada web browser Anda: [:url](:url)',
    'mark_read'           => 'Tandai Dibaca',
    'mark_read_all'       => 'Tandai Dibaca Semua',
    'empty'               => 'Cihuuy, gak ada notifikasi!',
    'new_apps'            => ':app tersedia <a href=":url">Lihat sekarang</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Update gagal di :domain',
            'description'   => 'Pembaruan :alias dari :current_version ke :new_version gagal dalam <strong>:step</strong> langkah dengan pesan berikut: :error_message',

        ],

        'slack' => [

            'description'   => 'Update gagal untuk :domain',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Impor selesai',
            'description'   => 'Impor telah selesai dan rekaman data telah tersedia di panel Anda.',

        ],

        'failed' => [

            'title'         => 'Impor gagal',
            'description'   => 'Tidak dapat mengimpor berkas dikarenakan masalah berikut:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Ekspor siap',
            'description'   => 'Berkas ekspor siap untuk diunduh dari tautan berikut:',

        ],

        'failed' => [

            'title'         => 'Ekspor gagal',
            'description'   => 'Tidak dapat membuat berkas ekspor dikarenakan masalah berikut:',

        ],

    ],

    'menu' => [

        'export_completed' => [

            'title'         => 'Ekspor siap',
            'description'   => 'File ekspor <strong>:type</strong> Anda siap untuk <a href=":url" target="_blank"><strong>mengunduh</strong></a>.',

        ],

        'export_failed' => [

            'title'         => 'Ekspor gagal',
            'description'   => 'Tidak dapat membuat file ekspor karena beberapa masalah. Periksa email Anda untuk detailnya.',

        ],

        'import_completed' => [

            'title'         => 'Impor telah selesai',
            'description'   => '<strong>:type</strong> baris <strong>:count</strong> data Anda berhasil diimpor.',

        ],

        'import_failed' => [

            'subject'       => 'Impor gagal',
            'description'   => 'Tidak dapat mengimpor file karena beberapa masalah. Periksa email Anda untuk detailnya.',

        ],

        'new_apps' => [

            'title'         => 'Aplikasi Baru',
            'description'   => '<strong>:name</strong> aplikasi keluar. Anda dapat <a href=":url">klik di sini</a> untuk melihat detailnya.',

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
            'description'   => 'Faktur <strong>:invoice_number</strong> dibuat berdasarkan lingkaran berulang Anda. Anda dapat <a href=":invoice_portal_link">klik di sini</a> untuk melihat detailnya dan melanjutkan pembayaran.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Faktur Berulang Baru',
            'description'   => 'Faktur <strong>:invoice_number</strong> dibuat berdasarkan <strong>:customer_name</strong> lingkaran berulang. Anda dapat <a href=":invoice_admin_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Faktur Dilihat',
            'description'   => '<strong>:customer_name</strong> telah melihat faktur <strong>:invoice_number</strong>. Anda dapat <a href=":invoice_admin_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Pembayaran Diterima',
            'description'   => 'Terima kasih atas pembayaran untuk <strong>:invoice_number</strong> invoice. Anda dapat <a href=":invoice_portal_link">klik di sini</a> untuk melihat detailnya.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Pembayaran Diterima',
            'description'   => 'Terima kasih atas pembayaran untuk <strong>:invoice_number</strong> invoice. Anda dapat <a href=":invoice_portal_link">klik di sini</a> untuk melihat detailnya.',

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
            'description'   => 'Tagihan <strong>:bill_number</strong> dibuat berdasarkan <strong>:vendor_name</strong> lingkaran berulang. Anda dapat <a href=":bill_admin_link">klik di sini</a> untuk melihat detailnya.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type membaca notifikasi ini!',
        'mark_read_all'         => ':type telah membaca semua notifikasi!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Konfigurasi Ikon Firefox',
            'description'  => '<span class="font-medium">Jika ikon Anda tidak muncul, harap;</span> <br /> <span class="font-medium">Harap Izinkan halaman untuk memilih font mereka sendiri, bukan pilihan Anda di atas< /span> <br /><br /> <span class="font-bold"> Pengaturan (Preferensi) > Font > Lanjutan </span>',

        ],

    ],

];
