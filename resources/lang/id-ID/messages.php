<?php

return [

    'success' => [
        'added'             => ':type ditambahkan!',
        'updated'           => ':type diperbaharui!',
        'deleted'           => ':type dihapus!',
        'duplicated'        => ':type duplikat!',
        'imported'          => ':type diimpor!',
        'enabled'           => ':type diaktifkan!',
        'disabled'          => ':type dinonaktifkan!',
    ],

    'error' => [
        'over_payment'      => 'Error: Pembayaran tidak ditambahkan! Jumlah yang Anda masukan melebihi nilai total :amount',
        'not_user_company'  => 'Kesalahan: Anda tidak diperbolehkan mengelola perusahaan ini!',
        'customer'          => 'Galat: Pengguna tidak dibuat! :name telah menggunakan alamat email ini.',
        'no_file'           => 'Kesalahan: Tidak ada file dipilih!',
        'last_category'     => 'Error: Tidak dapat menghapus kategori :type terakhir!',
        'change_type'       => 'Error: Can not change the type because it has :text related!',
        'invalid_apikey'    => 'Error: The API Key entered is invalid!',
        'import_column'     => 'Error: :message Nama sheet: :sheet. Baris ke :line.',
        'import_sheet'      => 'Error: Nama sheet tidak valid. Mohon untuk memeriksa contoh file yang tersedia.',
    ],

    'warning' => [
        'deleted'           => 'PeringatanL: Anda tidak boleh menghapus <b>:name</b> karena memiliki :text terkaitan.',
        'disabled'          => 'Peringatan: Anda tidak boleh menonaktifkan<b>:name</b> karena memiliki :text terkaitan.',
        'disable_code'      => 'Peringatan: Anda tidak diizinkan untuk menonaktifkan atau mengubah kurs <b>:name</b> karena memiliki keterkaitan dengan :text.',
        'payment_cancel'    => 'Warning: You have cancelled your recent :method payment!',
    ],

];
