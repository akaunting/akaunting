<?php

return [

    'success' => [
        'added'             => ':type ditambah!',
        'updated'           => ':type dikemaskini!',
        'deleted'           => ':type dipadamkan!',
        'duplicated'        => ':type disalinkan!',
        'imported'          => ':type telah diimport!',
        'exported'          => ':type telah diimport!',
        'enabled'           => ':type dihidupkan!',
        'disabled'          => ':type dimatikan!',
    ],

    'error' => [
        'over_payment'      => 'Ralat: Bayaran tidak dimasukkan! Amaun yang anda masukkan melebihi jumlah: :amount',
        'not_user_company'  => 'Ralat: Anda tidak dibenarkan untuk mengurus syarikat ini!',
        'customer'          => 'Ralat: Pengguna tidak dicipta! :name telah menggunakan alamat emel ini.',
        'no_file'           => 'Ralat: Tiada fail yang dipilih!',
        'last_category'     => 'Ralat: Tidak boleh padamkan kategori :type terakhir!',
        'change_type'       => 'Ralat: Tidak dibenarkan mengubah jenis sebab ianya telah :text berkaitan!',
        'invalid_apikey'    => 'Ralat: Kunci API yang dimasukkan tidak sah!',
        'import_column'     => 'Ralat: :message Nama helaian: :sheet. Baris bernombor: :line.',
        'import_sheet'      => 'Ralat: Nama helaian tidak sah. Sila periksa fail contoh.',
    ],

    'warning' => [
        'deleted'           => 'Amaran: Anda tidak dibenarkan untuk memadam <b>:name</b> kerana ia mempunyai kaitan dengan :text.',
        'disabled'          => 'Amaran: Anda tidak dibenarkan untuk mematikan <b>:name</b> kerana ia mempunyai kaitan dengan :text.',
        'disable_code'      => 'Amaran: Anda tidak dibenarkan untuk mematikan atau mengubah mata wang <b>:name</b> kerana ia mempunyai kaitan dengan :text.',
        'payment_cancel'    => 'Amaran: Anda telah membatalkan pembayaran :method baru-baru ini!',
    ],

];
