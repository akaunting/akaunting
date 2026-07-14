<?php

return [

    'whoops'              => 'Ups!',
    'hello'               => 'Halo!',
    'salutation'          => 'Hormat Kami,<br> :company_name',
    'subcopy'             => 'Jika anda menghadapi masalah apabila menekan butang ":text", salin dan tampal URL di bawah ke dalam pelayar web anda: [:url](:url)',
    'mark_read'           => 'Tanda Dibaca',
    'mark_read_all'       => 'Tanda Semua Dibaca',
    'empty'               => 'Woohoo, tiada pemberitahuan!',
    'new_apps'            => ':app tersedia. <a href=":url">Lihat sekarang</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ Kemas kini gagal di :domain',
            'description'   => 'Kemas kini :alias dari :current_version ke :new_version gagal dalam langkah <strong>:step</strong> dengan mesej berikut: :error_message',

        ],

        'slack' => [

            'description'   => 'Kemas kini gagal untuk :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Unduhan siap',
            'description'   => 'Berkas siap diunduh dari tautan berikut:',

        ],

        'failed' => [

            'title'         => 'Muat turun gagal',
            'description'   => 'Tidak dapat mencipta fail kerana isu berikut:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Impor selesai',
            'description'   => 'Impor telah selesai dan rekaman data telah tersedia di panel Anda.',

        ],

        'failed' => [

            'title'         => 'Import gagal',
            'description'   => 'Tidak dapat mengimport fail kerana isu berikut. Sila semak e-mel anda untuk butiran.',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Ekspor siap',
            'description'   => 'Berkas ekspor siap untuk diunduh dari tautan berikut:',

        ],

        'failed' => [

            'title'         => 'Eksport gagal',
            'description'   => 'Tidak dapat mencipta fail eksport kerana isu berikut. Sila semak e-mel anda untuk butiran.',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'E-mel :type tidak sah',
            'description'   => 'Alamat e-mel :email telah dilaporkan tidak sah, dan orang tersebut telah dinonaktifkan. Sila semak mesej ralat berikut dan betulkan alamat e-mel:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Unduhan siap',
            'description'   => 'Berkas <strong>:type</strong> Anda siap untuk <a href=":url" target="_blank"><strong>diunduh</strong></a>',

        ],

        'download_failed' => [

            'title'         => 'Unduhan gagal',
            'description'   => 'Tidak dapat membuat berkas dikarenakan beberapa masalah. Periksa email Anda untuk detailnya.',

        ],

        'export_completed' => [

            'title'         => 'Ekspor sedia',
            'description'   => 'Fail eksspor <strong>:type</strong> anda sedia untuk <a href=":url" target="_blank"><strong>dimuat turun</strong></a>',

        ],

        'export_failed' => [

            'title'         => 'Eksport gagal',
            'description'   => 'Tidak dapat mencipta fail eksport kerana isu berikut. Sila semak e-mel anda untuk butiran.',

        ],

        'import_completed' => [

            'title'         => 'Import selesai',
            'description'   => 'Data <strong>:type</strong> anda dengan <strong>:count</strong> data telah berjaya diimport.',

        ],

        'import_failed' => [

            'title'         => 'Import gagal',
            'description'   => 'Tidak dapat mengimport fail kerana isu berikut. Sila semak e-mel anda untuk butiran.',

        ],

        'new_apps' => [

            'title'         => 'Aplikasi Baru',
            'description'   => 'Aplikasi <strong>:name</strong> telah dikeluarkan. Anda boleh <a href=":url">klik di sini</a> untuk melihat butiran.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Invois Baru',
            'description'   => 'Invois <strong>:invoice_number</strong> dicipta. Anda boleh <a href=":invoice_portal_link">klik di sini</a> untuk melihat butiran dan meneruskan bayaran.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Invois Tertunggak',
            'description'   => 'Invois <strong>:invoice_number</strong> telah jatuh tempo pada <strong>:invoice_due_date</strong>. Anda boleh <a href=":invoice_portal_link">klik di sini</a> untuk melihat butiran dan meneruskan bayaran.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Invois Tertunggak',
            'description'   => 'Invois <strong>:invoice_number</strong> telah jatuh tempo pada <strong>:invoice_due_date</strong>. Anda boleh <a href=":invoice_admin_link">klik di sini</a> untuk melihat butiran.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Invois Berulang Baharu',
            'description'   => 'Invois <strong>:invoice_number</strong> dicipta berdasarkan kitaran berulang anda. Anda boleh <a href=":invoice_portal_link">klik di sini</a> untuk melihat butiran dan meneruskan bayaran.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Invois Berulang Baharu',
            'description'   => 'Invois <strong>:invoice_number</strong> dicipta berdasarkan kitaran berulang <strong>:customer_name</strong>. Anda boleh <a href=":invoice_admin_link">klik di sini</a> untuk melihat butiran.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Invois Dilihat',
            'description'   => '<strong>:customer_name</strong> telah melihat invois <strong>:invoice_number</strong>. Anda boleh <a href=":invoice_admin_link">klik di sini</a> untuk melihat butiran.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Bayaran Diterima',
            'description'   => 'Terima kasih atas bayaran untuk invois <strong>:invoice_number</strong>. Anda boleh <a href=":invoice_portal_link">klik di sini</a> untuk melihat butiran.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Bayaran Diterima',
            'description'   => 'Terima kasih atas bayaran untuk invois <strong>:invoice_number</strong>. Anda boleh <a href=":invoice_portal_link">klik di sini</a> untuk melihat butiran.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Bayaran Diterima',
            'description'   => ':customer_name merekodkan bayaran untuk invois <strong>:invoice_number</strong>. Anda boleh <a href=":invoice_admin_link">klik di sini</a> untuk melihat butiran.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Bil Tertunggak',
            'description'   => 'Bil <strong>:bill_number</strong> telah jatuh tempo pada <strong>:bill_due_date</strong>. Anda boleh <a href=":bill_admin_link">klik di sini</a> untuk melihat butiran.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Bil Berulang Baharu',
            'description'   => 'Bil <strong>:bill_number</strong> dicipta berdasarkan kitaran berulang <strong>:vendor_name</strong>. Anda boleh <a href=":bill_admin_link">klik di sini</a> untuk melihat butiran.',

        ],

        'invalid_email' => [

            'title'         => 'E-mel :type tidak sah',
            'description'   => 'Alamat e-mel <strong>:email</strong> telah dilaporkan tidak sah, dan orang tersebut telah dinonaktifkan. Sila semak dan betulkan alamat e-mel.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type telah membaca pemberitahuan ini!',
        'mark_read_all'         => ':type telah membaca semua pemberitahuan!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Konfigurasi Ikon Firefox',
            'description'  => '<span class="font-medium">Jika ikon anda tidak muncul, sila;</span> <br /> <span class="font-medium">Sila Benarkan halaman memilih fon mereka sendiri, dan bukan pilihan anda di atas</span> <br /><br /> <span class="font-bold">Tetapan (Keutamaan) > Fon > Lanjutan</span>',

        ],

    ],

];
